<?php

namespace app\controllers;

use app\components\MyController;
use yii\helpers\ArrayHelper;
use app\models\Region;
use app\models\Post;
use app\models\User;
use app\models\Org;
use app\models\State;
use app\models\ElectRequest;
use app\models\ElectVote;
use app\models\BillType;
use app\models\BillTypeField;
use app\models\GovermentFieldType;
use app\models\Population;
use app\models\Twitter;
use app\models\ElectResult;
use app\models\HoldingLicenseType;

class ModalController extends MyController
{
    
    public $layout = "api";
    
    public function actionCreateStateDialog($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }

            $forms = [['id' => 4, 'name' => 'Диктатура']];

            return $this->render("create_state_dialog", ['region' => $region, 'forms' => $forms]);
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
        return $this->render("tweet_about_human");
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
                $results        = [];
                $requests       = [];
                $sum_a_r        = 0;
                $sum_star       = 0;
                if (is_array($elect_requests)) {
                    foreach ($elect_requests as $request) {

                        if (($leader && is_null($request->user)) || (!$leader && is_null($request->party))) {
                            return $this->_r("Trololo " . $request->id);
                        }

                        $pr              = is_null($request->party) ? 0 : ($request->party->heart + $request->party->chart_pie / 10);
                        $abstract_rating = $leader ? $request->user->heart + $request->user->chart_pie / 10 + $pr / 10 : $pr;
                        $votes           = ElectVote::find()->where(["request_id" => $request->id])->all();
                        if (is_array($votes)) {
                            foreach ($votes as $vote) {
                                $abstract_rating += ($vote->user->star + $vote->user->heart / 10 + $vote->user->chart_pie / 100) / 10;
                            }
                        }
                        $results[]              = ['id' => $request->id, 'rating' => $abstract_rating];
                        $requests[$request->id] = $request;
                        $sum_a_r += $abstract_rating;
                        $sum_star += $leader ? $request->user->star : $request->party->star;
                    }
                    $yavka_time = 1 - ($org->next_elect - time()) / (24 * 60 * 60);
                    if ($yavka_time > 1) {
                        $yavka_time = 1;
                    }
                    $yavka_star = ($org->state->sum_star) ? $sum_star / $org->state->sum_star : 0;
                    $yavka      = $yavka_time * $yavka_star;

                    return $this->render("elect_exitpolls", ['requests' => $requests, 'results' => $results, 'sum_a_r' => $sum_a_r, 'org' => $org, 'yavka' => $yavka, 'leader' => $leader]);
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
            $bill_type = BillType::findByPk($id);
            if (is_null($bill_type)) {
                return $this->_r("Bill type not found");
            }
            $fields    = BillTypeField::find()->where(['bill_id' => $id])->all();

            $user = User::findByPk($this->viewer_id);
            if (is_null($user->state)) {
                return $this->_r("No citizenship");
            }

            $additional_data = [];

            if (is_array($fields)) {
                foreach ($fields as $field) {
                    switch ($field->type) {
                        case 'regions': // регионы исключая столицу
                            $additional_data['regions']               = Region::find()->where("state_id = {$user->state_id} AND code <> '{$user->state->capital}'")->orderBy('name')->all();
                            break;
                        case 'cities':
                            $additional_data['regions']               = Region::find()->where("state_id = {$user->state_id} AND code <> '{$user->state->capital}'")->orderBy('city')->all();
                            break;
                        case 'regions_all': // все регионы
                            $additional_data['regions']               = Region::find()->where(["state_id" => $user->state_id])->orderBy('name')->all();
                            break;
                        case 'cities_all':
                            $additional_data['regions']               = Region::find()->where(["state_id" => $user->state_id])->orderBy('city')->all();
                            break;
                        case 'goverment_field_types': // типы полей конституции
                            $additional_data['goverment_field_types'] = GovermentFieldType::find()->where(['hide' => 0])->all();
                            break;
                        case 'legislature_types':
                            $additional_data['legislature_types']     = [
                                ['id' => 1, 'display_name' => 'Стандартный парламент (10 мест)']
                            ];
                            break;
                        case 'elected_variants':
                            $additional_data['elected_variants']      = [];
                            if ($user->state->executiveOrg->isElected()) {
                                $additional_data['elected_variants'][]    = ['key' => $user->state->executive . '_0', 'name' => 'Выборы в организацию «' . $user->state->executiveOrg->name . '»'];
                            }
                            if ($user->state->executiveOrg->isLeaderElected()) {
                                $additional_data['elected_variants'][]    = ['key' => $user->state->executive . '_1', 'name' => 'Выборы лидера организации «' . $user->state->executiveOrg->name . '»'];
                            }
                            if ($user->state->legislatureOrg->isElected()) {
                                $additional_data['elected_variants'][]    = ['key' => $user->state->legislature . '_0', 'name' => 'Выборы в организации «' . $user->state->legislatureOrg->name . '»'];
                            }
                            if ($user->state->legislatureOrg->isLeaderElected()) {
                                $additional_data['elected_variants'][]    = ['key' => $user->state->legislature . '_1', 'name' => 'Выборы лидера организации «' . $user->state->legislatureOrg->name . '»'];
                            }
                            break;
                        case 'licenses':
                            $additional_data['licenses']              = HoldingLicenseType::find()->all();
                            break;
                        case 'orgs':
                            $additional_data['orgs']                  = Org::find()->where(['state_id' => $user->state_id])->all();
                            break;
                        case 'cores':
                            $additional_data['cores']                 = ['Не выделять'];
                            foreach ($user->state->regions as $region) {
                                foreach ($region->cores as $core) {
                                    if (!(in_array($core, $additional_data['cores']))) {
                                        $additional_data['cores'][] = $core;
                                    }
                                }
                            }


                            break;
                        default:

                            break;
                    }
                }
            }

            return $this->render("new_bill", ['bill_type' => $bill_type, 'fields' => $fields, 'additional_data' => $additional_data]);
        }
        else {
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
                            return $this->render("elect_leader_ind", ['org' => $org, 'leader' => $leader, 'elect_requests' => $elect_requests]);
                        case 'nation_party_vote':
                            return $this->render("elect_leader_party", ['org' => $org, 'leader' => $leader, 'elect_requests' => $elect_requests]);
                        default:
                            return $this->_r("Undefined elections type");
                    }
                }
                else {
                    switch ($org->dest) {
                        case 'nation_party_vote':
                            return $this->render("elect_party", ['org' => $org, 'leader' => $leader, 'elect_requests' => $elect_requests]);
                        default:
                            return $this->_r("Undefined elections type");
                    }
                }
            }
            else {
                return $this->_r("Elections not allowed");
            }
        }
        else {
            return $this->_r("Invalid organisation ID");
        }
    }

    public function actionElectRequest($org_id, $leader = 0)
    {
        $org_id = intval($org_id);
        $leader = intval($leader) ? 1 : 0;
        if ($org_id > 0) {
            $org  = Org::findByPk($org_id);
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
                                return $this->render("elect_leader_ind_req", ['org' => $org, 'leader' => $leader, 'user' => $user]);
                            }
                        case 'nation_party_vote':
                            if ($user->isPartyLeader()) {
                                
                                if (ElectRequest::find()->where(['org_id' => $org_id, 'party_id' => $user->party_id, 'leader' => 1])->count()) {
                                    return $this->_r("Allready have request from party");
                                }
                            
                                return $this->render("elect_leader_party_req", ['org' => $org, 'leader' => $leader, 'user' => $user]);
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
                            
                                return $this->render("elect_party_req", ['org' => $org, 'leader' => $leader, 'user' => $user]);
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
            if (intval($id) === 0) $code = $id;
            $region = ($code) ? Region::findByCode($code) : Region::findByPk($id);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }

            $user = User::findByPk($this->viewer_id);

            return $this->render("region_info", ['region' => $region, 'user' => $user]);
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
            $query  = Population::find()->where(['region_id' => $region->id]);

            return $this->render("region_population", ['region' => $region, 'people' => Population::getAllGroups($query), 'people_by_class' => Population::getGroupsByClass($query)]);
        }
        else {
            return $this->_r("Invalid code");
        }
    }

    public function actionRegionResurses($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region)) {
                return $this->_r("Region not found");
            }

            return $this->render("region_resurses", ['region' => $region]);
        }
        else {
            return $this->_r("Invalid code");
        }
    }

    public function actionGetTwitterFeed($time, $offset, $uid = 0)
    {
        $time   = intval($time);
        $offset = intval($offset);
        $uid    = intval($uid);
        if ($time && $offset) {
            if ($uid) {
                $tweets = Twitter::find()->where("uid = {$uid} AND date < {$time}")->offset($offset)->limit(5)->orderBy('date DESC')->all();
            }
            else {
                $tweets = Twitter::find()->where("retweets > 0 AND date < " . $time)->offset($offset)->limit(5)->orderBy('date DESC')->all();
            }

            return $this->render("twitter_feed", ['tweets' => $tweets, 'viewer_id' => $this->viewer_id]);
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

            $results = ElectResult::find()->where("org_id = {$state->legislature} OR org_id = {$state->executive}")->orderBy('date')->all();
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

}
