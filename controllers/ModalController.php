<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    yii\helpers\ArrayHelper,
    app\models\Region,
    app\models\Post,
    app\models\User,
    app\models\UserSearch,
    app\models\Org,
    app\models\State,
    app\models\ElectRequest,
    app\models\ElectVote,
    app\models\bills\proto\BillProto,
    app\models\bills\proto\BillProtoField,
    app\models\articles\proto\ArticleProto,
    app\models\articles\Article,
    app\models\Population,
    app\models\Twitter,
    app\models\ElectResult,
    app\models\licenses\proto\LicenseProto,
    app\models\Holding,
    app\models\factories\proto\FactoryProtoCategory,
    app\models\factories\FactoryAuction,
    app\models\Utr as Unnp,
    app\models\Ideology,
    app\models\factories\Factory,
    app\models\resources\Resource,
    app\models\resources\ResourceCost,
    app\models\resources\proto\ResourceProto,
    app\models\factories\FactoryAutobuySettings,
    app\models\Place,
    app\models\Religion,
    app\models\PopClass,
    app\models\PopNation,
    app\models\massmedia\Massmedia,
    app\models\massmedia\MassmediaEditor;

class ModalController extends MyController {

    public $layout = "api";

    public function actionCreateStateDialog($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }

            $forms = [['id' => 4, 'name' => 'Диктатура'], ['id' => 2, 'name' => 'Президентская республика'], ['id' => 3, 'name' => 'Парламентская республика']];

            return $this->render("create-state-dialog", ['region' => $region, 'forms' => $forms]);
        } else {
            return $this->_r("Invalid code");
        }
    }

    public function actionNaznach($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $post = Post::findByPk($id);
            if (is_null($post)) {
                return $this->_r("Post not found");
            }

            if (!($post->org->dest === 'dest_by_leader' && intval($post->org->leader->user->id) === $this->viewer_id && $id !== $post->org->leader_post)) {
                return $this->_r("No access");
            }

            $people = User::find()->where(['state_id' => $post->org->state_id, 'post_id' => 0])->orderBy('`star` + `heart` + `chart_pie` DESC')->all();

            return $this->render("naznach", ['post' => $post, 'people' => $people]);
        } else {
            return $this->_r("Invalid post ID");
        }
    }

    public function actionTweetAboutHuman()
    {
        return $this->render("tweet-about-human");
    }

    public function actionElectExitpolls($org_id, $leader = 0)
    {
        $org_id = intval($org_id);
        $leader = intval($leader);
        if ($org_id > 0) {
            $org = Org::findByPk($org_id);
            if (is_null($org)) {
                return $this->_r("Organisation not found");
            }

            if ($org->next_elect > time()) {

                $elect_requests = ElectRequest::find()->where(["org_id" => $org_id, "leader" => $leader])->all();
                $results = [];
                $requests = [];
                $sum_a_r = 0;
                $sum_star = 0;
                if (count($elect_requests)) {
                    foreach ($elect_requests as $request) {

                        if (($leader && is_null($request->user)) || (!$leader && is_null($request->party))) {
                            return $this->_r("Trololo " . $request->id);
                        }

                        $pr = is_null($request->party) ? 0 : ($request->party->heart + $request->party->chart_pie / 10);
                        $abstract_rating = $leader ? $request->user->heart + $request->user->chart_pie / 10 + $pr / 10 : $pr;
                        $votes = ElectVote::find()->where(["request_id" => $request->id])->all();
                        if (is_array($votes)) {
                            foreach ($votes as $vote) {
                                $abstract_rating += ($vote->user->star + $vote->user->heart / 10 + $vote->user->chart_pie / 100) / 10;
                            }
                        }
                        $results[] = ['id' => $request->id, 'rating' => $abstract_rating];
                        $requests[$request->id] = $request;
                        $sum_a_r += $abstract_rating;
                        $sum_star += $leader ? $request->user->star : $request->party->star;
                    }
                    $yavka_time = 1 - ($org->next_elect - time()) / (24 * 60 * 60);
                    if ($yavka_time > 1) {
                        $yavka_time = 1;
                    }
                    $yavka_star = ($org->state->sum_star) ? $sum_star / $org->state->sum_star : 0;
                    $yavka = $yavka_time * $yavka_star;

                    return $this->render("elect-exitpolls", ['requests' => $requests, 'results' => $results, 'sum_a_r' => $sum_a_r, 'org' => $org, 'yavka' => $yavka, 'leader' => $leader]);
                } else {
                    return $this->_r("No requests on elections");
                }
            } else {
                return $this->render("cap/elects-ended", ['org' => $org]);
            }
        } else {
            return $this->_r("Invalid organisation ID");
        }
    }

    public function actionNewBill($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $bill_type = BillProto::findByPk($id);
            if (is_null($bill_type)) {
                return $this->_r("Bill type not found");
            }

            $user = $this->getUser();
            if (is_null($user->state)) {
                return $this->_r("No citizenship");
            }

            return $this->render("newbill/{$id}", ['bill_type' => $bill_type, 'user' => $user]);
        } else {
            return $this->_r("Invalid bill type ID");
        }
    }

    public function actionElectVote($org_id, $leader = 0)
    {
        $org_id = intval($org_id);
        $leader = intval($leader);
        if ($org_id > 0) {
            $org = Org::findByPk($org_id);
            if (is_null($org)) {
                return $this->_r("Organisation not found");
            }

            $user = User::findByPk($this->viewer_id);
            if ($user->state_id !== $org->state_id) {
                return $this->_r("Only citizens can vote");
            }

            if (($leader === 0 && $org->isElected()) || ($leader && $org->isLeaderElected())) {

                $elect_requests_ids = implode(",", ArrayHelper::map(ElectRequest::find()->where(["org_id" => $org_id, "leader" => $leader])->asArray()->all(), 'id', 'id'));
                if ($elect_requests_ids) {
                    $allready_voted = ElectVote::find()->where("request_id IN ({$elect_requests_ids}) AND uid = {$this->viewer_id}")->count();
                    if (intval($allready_voted)) {
                        return $this->_r("Allready voted");
                    }
                }

                $elect_requests = ElectRequest::find()->where(["org_id" => $org_id, "leader" => $leader])->all();

                if ($leader) {
                    switch ($org->leader_dest) {
                        case 'nation_individual_vote':
                            return $this->render("elect-leader-ind", ['org' => $org, 'leader' => $leader, 'elect_requests' => $elect_requests]);
                        case 'nation_party_vote':
                            return $this->render("elect-leader-party", ['org' => $org, 'leader' => $leader, 'elect_requests' => $elect_requests]);
                        default:
                            return $this->_r("Undefined elections type");
                    }
                } else {
                    switch ($org->dest) {
                        case 'nation_party_vote':
                            return $this->render("elect-party", ['org' => $org, 'leader' => $leader, 'elect_requests' => $elect_requests]);
                        default:
                            return $this->_r("Undefined elections type");
                    }
                }
            } else {
                return $this->_r("Elections not allowed");
            }
        } else {
            return $this->_r("Invalid organisation ID");
        }
    }

    public function actionElectRequest($org_id, $leader = 0)
    {
        $org_id = intval($org_id);
        $leader = intval($leader) ? 1 : 0;
        if ($org_id > 0) {
            $org = Org::findByPk($org_id);
            if (is_null($org)) {
                return $this->_r("Organisation not found");
            }
            $user = User::findByPk($this->viewer_id);
            if ($user->state_id !== $org->state_id) {
                return $this->_r("Only citizens can requests");
            }

            if (($leader === 0 && $org->isElected()) || ($leader && $org->isLeaderElected())) {

                if ($leader) {

                    switch ($org->leader_dest) {
                        case 'nation_individual_vote':
                            if (ElectRequest::find()->where(['org_id' => $org_id, 'candidat' => $user->id, 'leader' => 1])->count()) {
                                return $this->_r("Allready have request");
                            } else {
                                return $this->render("elect-leader-ind-req", ['org' => $org, 'leader' => $leader, 'user' => $user]);
                            }
                        case 'nation_party_vote':
                            if ($user->isPartyLeader()) {

                                if (ElectRequest::find()->where(['org_id' => $org_id, 'party_id' => $user->party_id, 'leader' => 1])->count()) {
                                    return $this->_r("Allready have request from party");
                                }

                                return $this->render("elect-leader-party-req", ['org' => $org, 'leader' => $leader, 'user' => $user]);
                            } else {
                                return $this->_r("Only party leader can make request");
                            }
                        default:
                            return $this->_r("Undefined elections type");
                    }
                } else {
                    switch ($org->dest) {
                        case 'nation_party_vote':
                            if ($user->isPartyLeader()) {

                                if (ElectRequest::find()->where(['org_id' => $org_id, 'party_id' => $user->party_id, 'leader' => 0])->count()) {
                                    return $this->_r("Allready have request from party");
                                }

                                return $this->render("elect-party-req", ['org' => $org, 'leader' => $leader, 'user' => $user]);
                            } else {
                                return $this->_r("Only party leader can make request");
                            }
                        default:
                            return $this->_r("Undefined elections type");
                    }
                }
            } else {
                return $this->_r("Elections not allowed");
            }
        } else {
            return $this->_r("Invalid organisation ID");
        }
    }

    public function actionRegionInfo($code = false, $id = false)
    {
        if ($code || $id) {
            if (intval($id) === 0)
                $code = $id;
            $region = ($code) ? Region::findByCode($code) : Region::findByPk($id);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }

            $user = User::findByPk($this->viewer_id);

            return $this->render("region-info", ['region' => $region, 'user' => $user]);
        } else {
            return $this->_r("Invalid code or ID");
        }
    }

    public function actionRegionPopulation($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }
            $query = Population::find()->where(['region_id' => $region->id]);

            return $this->render("region-population", ['region' => $region, 'people' => Population::getAllGroups($query), 'people_by_class' => Population::getGroupsByClass($query)]);
        } else {
            return $this->_r("Invalid code");
        }
    }

    public function actionRegionResources($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }

            return $this->render("region-resources", ['region' => $region]);
        } else {
            return $this->_r("Invalid code");
        }
    }

    public function actionGetTwitterFeed($time, $offset, $uid = 0)
    {
        $time = intval($time);
        $offset = intval($offset);
        $uid = intval($uid);
        if ($time && $offset) {
            if ($uid) {
                $tweets = Twitter::find()->where("uid = {$uid} AND date < {$time}")->offset($offset)->limit(5)->orderBy('date DESC')->all();
            } else {
                $tweets = Twitter::find()->where("retweets > 0 AND date < " . $time)->offset($offset)->limit(5)->orderBy('date DESC')->all();
            }

            return $this->render("twitter-feed", ['tweets' => $tweets, 'viewer_id' => $this->viewer_id]);
        } else {
            return $this->_r("Invalid params");
        }
    }

    public function actionOldElections($state_id)
    {
        $state_id = intval($state_id);
        if ($state_id) {
            $state = State::findByPk($state_id);
            if (is_null($state))
                return $this->_r("State not found");

            $results = ElectResult::find()->where("org_id = {$state->legislature} OR org_id = {$state->executive}")->orderBy('date DESC')->all();
            return $this->render("old-elections", ['results' => $results]);
        } else {
            return $this->_r("Invalid params");
        }
    }

    public function actionElectionsResult($id)
    {
        $id = intval($id);
        if ($id) {
            $result = ElectResult::findByPk($id);

            return $this->render("elect-result", ['result' => $result]);
        } else {
            return $this->_r("Invalid params");
        }
    }

    public function actionAccountSettings()
    {
        return $this->render("account-settings", ['user' => $this->getUser()]);
    }

    public function actionBuildFactory($region_id, $holding_id)
    {
        $region = Region::findByPk($region_id);
        $holding = Holding::findByPk($holding_id);
        $factoryCategories = FactoryProtoCategory::find()->all();

        return $this->render("build-factory", [
                    'region' => $region,
                    'holding' => $holding,
                    'user' => $this->getUser(),
                    'factoryCategories' => $factoryCategories
        ]);
    }

    
    public function actionLicensesOptions($holding_id, $state_id)
    {
        $holding = Holding::findByPk($holding_id);
        $state = State::findByPk($state_id);
        $licensesProtos = LicenseProto::find()->all();

        return $this->render("licenses-options",[
            'licensesProtos' => $licensesProtos,
            'holding' => $holding,
            'state' => $state
        ]);
    }

    public function actionLicensesControlsChange($license_proto_id)
    {
        $license_proto_id = intval($license_proto_id);
        if ($license_proto_id > 0) {
            $licenseType = LicenseProto::findByPk($license_proto_id);
            if (is_null($licenseType)) {
                return $this->_r("License type not found");
            }

            $user = $this->getUser();
            if (is_null($user->state)) {
                return $this->_r("Have not citizenship");
            }

            $stateLicense = $user->state->getLicenseRuleByPrototype($licenseType);
            return $this->render("licenses-controls-change", ["licenseType" => $licenseType, "stateLicense" => $stateLicense]);
        } else {
            return $this->_r("Invalid license type ID");
        }
    }

    public function actionGovermentFieldValue($proto_id)
    {
        $proto_id = intval($proto_id);
        if ($proto_id > 0) {

            $gft = ArticleProto::findByPk($proto_id);
            if (is_null($gft)) {
                return $this->_r("Govement field type not found");
            }

            $user = $this->getUser();
            if (is_null($user->state)) {
                return $this->_r("Have not citizenship");
            }

            $gfv = Article::findOrCreate(['state_id' => $user->state_id, 'proto_id' => $proto_id], true, ['value' => $gft->default_value]);

            return $this->render("newbill/goverment-field-value", ['gfv' => $gfv, 'gft' => $gft]);
        } else {
            return $this->_r("Invalid govement field type ID");
        }
    }

    public function actionBuildLineVariants($proto_id, $region1_id)
    {
        $regionBase = Region::findByPk($region1_id);
        if ($regionBase) {
            $regions = $regionBase->getBordersArray();
            
            return $this->render('build-line-variants',[
                'regions' => $regions,
                'regionBase' => $regionBase
            ]);
        } else {
            return $this->_r("Invalid region ID");
        }
    }
    
    public function actionFactoryAuctionInfo($id, $unnp)
    {
        $id = intval($id);
        $unnp = intval($unnp);
        
        $auc = FactoryAuction::findByPk($id);
        if (is_null($auc)) {
            return $this->_r("Invalid auction ID");
        }
        
        $n = Unnp::findByPk($unnp);
        if (is_null($n) || is_null($n->master)) {
            return $this->_r("Invalid UNNP");
        }
        
        return $this->render("factory-auction-info",[
            'auction' => $auc,
            'master' => $n->master
        ]);
    }
    
    public function actionChangeIdeology()
    {
        $ideologies = Ideology::find()->orderBy('d ASC')->all();
        return $this->render('change-ideology',[
            'ideologies' => $ideologies,
            'user' => $this->user
        ]);
    }
    
    public function actionManagerFactorySetResourceSelling($factory_id, $resource_proto_id)
    {        
        if (intval($factory_id) <= 0) {
            return $this->_r("Invalid factory ID");
        }
        if (intval($resource_proto_id) <= 0) {
            return $this->_r("Invalid resource prototype ID");
        }
        
        $factory = Factory::findByPk($factory_id);
        if (is_null($factory)) {
            return $this->_r("Factory not found");
        }
        
        if ($factory->manager_uid !== $this->viewer_id) {
            return $this->_r("Not allowed");
        }
        
        $resource = $factory->getStorage($resource_proto_id);
        if (is_null($resource)) {
            return $this->_r("Resource not found");
        }
        
        return $this->render('factory-set-resource-selling',[
            'factory' => $factory,
            'resource' => $resource
        ]);
    }
    
    public function actionMarketResources($resource_proto_id, $unnp = false)
    {
        
        if (intval($resource_proto_id) <= 0) {
            return $this->_r("Invalid resource prototype ID");
        }
        
        $resProto = ResourceProto::findByPk($resource_proto_id);
        if (is_null($resProto)) {
            return $this->_r("Resource prototype not found");
        }
        
        if (intval($unnp) > 0) {
            $viewerUnnp = Unnp::findByPk($unnp);
            if (is_null($viewerUnnp)) {
                return $this->_r("Unnp not found");
            }

            $viewer = $viewerUnnp->master;
            if (is_null($viewer)) {
                return $this->_r("Unnp is invalid");
            }

            if ($viewer->getUnnpType() !== Unnp::TYPE_FACTORY || $viewer->manager_uid !== $this->viewer_id) {
                return $this->_r("Not allowed");
            }
        }
        
        $query = ResourceCost::find()
                ->join('LEFT JOIN', Resource::tableName(), Resource::tableName().'.id = '.ResourceCost::tableName().'.resource_id')
                ->where([Resource::tableName().'.proto_id' => $resProto->id]);
        
        if ($resProto->isStorable()) {
            $query = $query->andWhere(['>',Resource::tableName().'.count',0]);
        }
        
        if (intval($unnp) > 0) {            
            $query = $query->andWhere(['or',['holding_id'=>null],['holding_id'=>$viewer->holding_id]])
                ->andWhere(['or',['state_id'=>null],['state_id'=>$viewer->getLocatedStateId()]]);
        } else {
            $query = $query->andWhere(['holding_id'=>null])
                    ->andWhere(['state_id'=>null]);
        }
        
        $costs = $query->with('resource')
                ->with('resource.place')
                ->orderBy(ResourceCost::tableName().'.cost ASC, '.Resource::tableName().'.quality DESC')
                ->groupBy(Resource::tableName().'.place_id')
                ->all();
        
        // delete not working nonstorables
        if (!$resProto->isStorable()) {
            foreach ($costs as $i => $cost) {
                /* @var $cost ResourceCost */
                if ($cost->resource->place->object->getPlaceType() === Place::TYPE_FACTORY && $cost->resource->place->object->status !== Factory::STATUS_ACTIVE) {
                    unset($costs[$i]);
                }
            }
            sort($costs);
        }
                            
        return $this->render('market-resources',[
            'resProto' => $resProto,
            'costs' => $costs,
            'readOnly' => !(intval($unnp) > 0)
        ]);
        
    }
    
    public function actionResourceCostInfo($id, $unnp)
    {
        if (intval($id) <= 0) {
            return $this->_r("Invalid resource cost ID");
        }
        if (intval($unnp) <= 0) {
            return $this->_r("Invalid UNNP");
        }
        
        $resCost = ResourceCost::findByPk($id);
        if (is_null($resCost)) {
            return $this->_r("Resource cost not found");
        }
        
        $viewerUnnp = Unnp::findByPk($unnp);
        if (is_null($viewerUnnp)) {
            return $this->_r("Unnp not found");
        }
        
        $viewer = $viewerUnnp->master;
        if (is_null($viewer)) {
            return $this->_r("Unnp is invalid");
        }
        
        if ($viewer->getUnnpType() !== Unnp::TYPE_FACTORY || $viewer->manager_uid !== $this->viewer_id) {
            return $this->_r("Not allowed");
        }
        
        return $this->render('resource-cost-info',[
            'resCost' => $resCost,
            'viewer' => $viewer
        ]);
    }
        
    public function actionManagerFactorySetResourceAutobuy($factory_id, $resource_proto_id)
    {        
        if (intval($factory_id) <= 0) {
            return $this->_r("Invalid factory ID");
        }
        if (intval($resource_proto_id) <= 0) {
            return $this->_r("Invalid resource prototype ID");
        }
        
        $factory = Factory::findByPk($factory_id);
        if (is_null($factory)) {
            return $this->_r("Factory not found");
        }
        
        if ($factory->manager_uid !== $this->viewer_id) {
            return $this->_r("Not allowed");
        }
        
        $resource = $factory->getStorage($resource_proto_id);
        if (is_null($resource)) {
            return $this->_r("Resource not found");
        }
        
        $settings = FactoryAutobuySettings::findOrCreate([
            'factory_id' => $factory_id,
            'resource_proto_id' => $resource_proto_id
        ]);
        return $this->render('factory-set-resource-autobuy',[
            'factory' => $factory,
            'resource' => $resource,
            'settings' => $settings
        ]);
    }
    
    public function actionFactoryDealings($id)
    {
        if (intval($id) <= 0) {
            return $this->_r("Invalid factory ID");
        }
        
        $factory = Factory::findByPk($id);
        if (is_null($factory)) {
            return $this->_r("Factory not found");
        }
        
        if ($factory->manager_uid !== $this->viewer_id) {
            return $this->_r("Not allowed");
        }
        
        return $this->render('factory-dealings',['factory'=>$factory]);
    }
    
    public function actionBuildFactorySelectRegion($holding_id)
    {
        if (intval($holding_id) <= 0) {
            return $this->_r("Invalid holding ID");
        }
        
        $holding = Holding::findByPk($holding_id);
        if (is_null($holding)) {
            return $this->_r("Holding not found");
        }
        
        if (!$holding->isUserController($this->viewer_id)) {
            return $this->_r("Not allowed");
        }
                
        $regions = Region::find()->with('state')->with('state.articles')->orderBy('state_id')->all();
        
        return $this->render('build-factory-select-region', [
            'holding' => $holding,
            'regions' => $regions
        ]);
    }

    public function actionHoldingNewLicense($holding_id)
    {
        if (intval($holding_id) <= 0) {
            return $this->_r("Invalid holding id");
        }
        
        $holding = Holding::findByPk($holding_id);
        if (is_null($holding)) {
            return $this->_r("Holding not found");
        }
        
        $states = State::find()->with('licenses')->all();
        $licenses = LicenseProto::find()->all();
        
        return $this->render('holding-new-license',[
            'states' => $states,
            'licenses' => $licenses,
            'holding' => $holding
        ]);
    }
    
    public function actionSetSuccessor()
    {
        if (!$this->user->isOrgLeader() || $this->user->post->org->leader_dest !== Org::DEST_UNLIMITED) {
            return $this->_r("Not allowed");
        }
        
        $users = User::find()->where([
                    'state_id' => $this->user->state_id
                ])
                ->andWhere(['<>', 'id', $this->user->id])
                ->orderBy('star DESC')
                ->with('party')
                ->all();
        
        return $this->render('set-successor',[
            'users' => $users
        ]);
        
    }
    
    public function actionCreateNewspaper()
    {
        $user = $this->user;
        
        $currentState = $user->region ? $user->region->state : null;
        if (!$currentState) {
            return $this->_r("Невозможно создать газету, находясь на этой территории");
        }
        $holdings = $user->holdings;
        if (!count($holdings)) {
            return $this->_r("Невозможно создать газету, не будучи директором ни одной компании");
        }
        $popClasses = PopClass::find()->all();
        $popNations = PopNation::find()->all();
        $religions = Religion::find()->all();
        $ideologies = Ideology::find()->orderBy('d')->all();
        $regions = Region::find()->with('state')->orderBy('state_id')->all();
                
        return $this->render('newspapers/create', [
            'user' => $user,
            'currentState' => $currentState,
            'holdings' => $holdings,
            'popClasses' => $popClasses,
            'popNations' => $popNations,
            'religions' => $religions,
            'ideologies' => $ideologies,
            'regions' => $regions
        ]);
    }
    
    public function actionAddEditor($massmediaId)
    {
        $massmedia = Massmedia::findByPk($massmediaId);
        if (is_null($massmedia)) {
            return $this->_r("Invalid massmedia ID");
        }
        
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('massmedia/add-editor', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'massmedia' => $massmedia
        ]);
    }
    
    public function actionRuleEditor($massmediaId, $userId)
    {
        $massmedia = Massmedia::findByPk($massmediaId);
        if (is_null($massmedia)) {
            return $this->_r("Invalid massmedia ID");
        }
        
        $user = User::findByPk($userId);
        if (is_null($user)) {
            return $this->_r("Invalid user ID");
        }
        
        $rule = MassmediaEditor::findOrCreate([
            'userId' => $userId,
            'massmediaId' => $massmediaId
        ], false);
        
        return $this->render('massmedia/rule-editor', [
            'user' => $user,
            'massmedia' => $massmedia,
            'rule' => $rule
        ]);
    }
    
}
