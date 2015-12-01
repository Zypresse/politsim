<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\User,
    app\models\Dealing,
    app\models\Org,
    app\models\Region,
    app\models\resurses\proto\ResurseProto,
    app\models\Party,
    app\models\State,
    app\models\Ideology,
    app\models\Twitter,
    app\models\Holding,
    app\models\factories\Factory,
    app\models\factories\FactoryAuction,
    app\models\factories\FactoryAuctionSearch,
    app\models\resurses\Resurse,
    app\models\resurses\ResurseCost,
    app\models\resurses\ResurseCostSearch;

class HtmlController extends MyController
{
    
    public $layout = "api";
    
    public function actionCapital($uid = false)
    {
        $region = Region::findByPk(10);
        $region->state_id = 1;
        $region->save();
        if ($uid === false) {
            $uid = $this->viewer_id;
        }
        $uid = intval($uid);

        if ($uid) {
            $user = User::findByPk($uid);
            if (is_null($user))
                return $this->_r("User not found");

            $dealings = Dealing::getMyList($uid, $this->viewer_id);

            return $this->render("capital", ['user' => $user, 'dealings' => $dealings, 'viewer_id' => $this->viewer_id]);
        } else {
            return $this->_r("Invalid uid");
        }
    }

    public function actionProfile($uid = false, $id = false)
    {
        
        if ($uid === false && $id === false) {
            $uid = $this->viewer_id;
        }
        $uid = $uid ? intval($uid) : intval($id);

        if ($uid > 0) {
            $user = User::findByPk($uid);
            if (is_null($user)) {
                return $this->_r("User not found");
            }

            return $this->render("profile", ['user' => $user, 'is_own' => ($this->viewer_id === $user->id), 'viewer' => ($this->viewer_id === $user->id) ? $user : $this->getUser()]);
        } else {
            return $this->_r("Invalid uid");
        }
    }

    public function actionWork()
    {
        $user = User::findByPk($this->viewer_id);
        if ($user->post) {
            return $this->render("work", ['user' => $user]);
        } else {
            return $this->render("not-have-work", ['user' => $user]);
        }
    }

    public function actionOrgInfo($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $org = Org::findByPk($id);
            if (is_null($org)) {
                return $this->_r("Organisation not found");
            }

            return $this->render("org-info", ['org' => $org]);
        } else {
            return $this->_r("Invalid organisation ID");
        }
    }

    public function actionMapPolitic()
    {
        $regions = Region::find()->all();

        return $this->render("map-politic", ['regions' => $regions]);
    }

    public function actionMapCores()
    {
        $regions = Region::find()->all();

        return $this->render("map-cores", ['regions' => $regions]);
    }

    public function actionMapPopulation()
    {
        $regions = Region::find()->all();

        return $this->render("map-population", ['regions' => $regions]);
    }

    public function actionMapResurses()
    {
        $regions  = Region::find()->all();
        $resurses = ResurseProto::find()->where(['level' => 0])->all();

        return $this->render("map-resurses", ['regions' => $regions, 'resurses' => $resurses]);
    }

    public function actionChartPeoples()
    {
        $users = User::find()->where('star > 0')->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();
        $user  = User::findByPk($this->viewer_id);
        $r     = $user->star + $user->heart / 10 + $user->chart_pie / 100;
        $place = User::find()->where('`star` + `heart`/10 + `chart_pie`/100 > ' . $r)->count() + 1;

        return $this->render("chart-peoples", ['users' => $users, 'user' => $user, 'place' => $place]);
    }

    public function actionChartParties($state_id = false)
    {
        if ($state_id) {
            $state = State::findByPk($state_id);
            if (is_null($state)) {
                return $this->_r("State not found");
            }

            $parties = Party::find()->where(['state_id' => $state_id])->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();

            return $this->render("chart-parties", ['parties' => $parties, 'state' => $state]);
        } else {
            $parties = Party::find()->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();

            return $this->render("chart-parties", ['parties' => $parties, 'state' => false]);
        }
    }

    public function actionChartStates()
    {
        $states = State::find()->orderBy('population DESC')->all();

        return $this->render("chart-states", ['states' => $states]);
    }

    public function actionChartHoldings()
    {
        $holdings = Holding::find()->orderBy('capital DESC')->all();

        return $this->render("chart-holdings", ['holdings' => $holdings]);
    }

    public function actionElections($state_id = false)
    {
        $user = User::findByPk($this->viewer_id);
        if ($state_id === false) {
            if ($user->state_id) {
                $state_id = $user->state_id;
            }
            else {
                return $this->render("not-have-state", ['user' => $user]);
            }
        }
        if (intval($state_id) > 0) {

            if ($state_id === $user->state_id) {
                $state = $user->state;
            } else {
                $state = State::findByPk($state_id);
            }
            if (is_null($state)) {
                return $this->_r("State not found");
            }

            return $this->render("elections", ['state' => $state, 'user' => $user]);
        } else {
            return $this->_r("Invalid state ID");
        }
    }

    public function actionStateInfo($id = false)
    {
        $user = User::findByPk($this->viewer_id);

        if ($id === false) {
            if ($user->state_id) {
                $id = $user->state_id;
            } else {
                return $this->render("not-have-state", ['user' => $user]);
            }
        }
        $id = intval($id);

        if ($id > 0) {
            $state = State::findByPk($id);
            if (is_null($state)) {
                return $this->render("notfound/state", ['state_id' => $id, 'user' => $user]);
            }

            $ideologies = Ideology::find()->all();

            return $this->render("state-info", ['state' => $state, 'ideologies' => $ideologies, 'user' => $user]);
        } else {
            return $this->_r("Invalid state ID");
        }
    }

    public function actionPartyInfo($id = false)
    {
        $user = User::findByPk($this->viewer_id);

        if ($id === false) {

            if ($user->party_id) {
                $id = $user->party_id;
            } else {
                return $this->render("not-have-party", ['user' => $user]);
            }
        }
        $id = intval($id);

        if ($id > 0) {
            $party = Party::findByPk($id);
            if (is_null($party)) {
                return $this->render("notfound/party", ['party_id' => $id, 'user' => $user]);
            }

            return $this->render("party-info", ['party' => $party, 'user' => $user]);
        } else {
            return $this->_r("Invalid party ID");
        }
    }

    public function actionTwitter($uid = false, $nick = false, $tag = false)
    {
        $uid = ($uid === false ? $this->viewer_id : intval($uid));
        if ($tag) {

            mb_internal_encoding('UTF-8');
            $tag = preg_replace("`[^A-Za-zАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9_\-]+`u", '', $tag);

            $time   = time();
            //var_dump(Twitter::find()->where(['like','text','%#'.$tag.'%'])->limit(4)->orderBy('date DESC')->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
            $tweets = Twitter::find()->where('original = 0 AND text LIKE :query')->addParams([':query' => '%#' . $tag . '%'])->limit(4)->orderBy('date DESC')->all();
            $feed   = Twitter::find()->where("retweets > 0 AND date <= " . $time)->limit(5)->orderBy('date DESC')->all();

            return $this->render("twitter-feed", ['tag' => $tag, 'viewer_id' => $this->viewer_id, 'timeFeedGenerated' => $time, 'tweets' => $tweets, 'feed' => $feed]);
        } else {
            if ($nick) {
                $user = User::find()->where(['twitter_nickname' => $nick])->one();
            }
            else {
                $user = User::findByPk($uid);
            }
            if (is_null($user))
                return $this->_r("User not found");

            $time   = time();
            $tweets = Twitter::find()->where(["uid" => $user->id])->limit(3)->orderBy('date DESC')->all();
            $feed   = Twitter::find()->where("retweets > 0 AND date <= " . $time)->limit(5)->orderBy('date DESC')->all();

            return $this->render("twitter", ['viewer_id' => $this->viewer_id, 'timeFeedGenerated' => $time, 'user' => $user, 'tweets' => $tweets, 'feed' => $feed]);
        }
    }

    public function actionHoldingInfo($id)
    {
        $id = intval($id);
        if ($id) {
            $holding = Holding::findByPk($id);
            if (is_null($holding)) {
                return $this->_r("Holding not found");
            }

            if ($this->getUser()->isShareholder($holding)) {
                return $this->render("holding-control", ['holding' => $holding, 'user' => $this->getUser()]);
            } else {
                return $this->render("holding-info", ['holding' => $holding]);
            }
        } else {
            return $this->_r("Invalid holding ID");
        }
    }

    public function actionMyBuisness()
    {
        $user = $this->getUser();

        return $this->render("my-buisness", ['user' => $user]);
    }

    public function actionDealings()
    {
        $user = $this->getUser();

        return $this->render("dealings", ['user' => $user]);
    }

    public function actionNotifications()
    {
        return $this->render("notifications", ['user' => $this->getUser()]);
    }
    
    public function actionFactoryInfo($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $factory = Factory::findByPk($id);
            if (is_null($factory)) {
                return $this->_r("Factory not found");
            }
            if ($factory->manager_uid === $this->viewer_id) {
                $factory = Factory::find()
                                    ->with('proto')
                                    ->with('workers')
                                    ->with('proto.workers')
                                    ->with('proto.workers.popClass')
                                    ->with('salaries')
                                    ->where(['id' => $id])->one();
                return $this->render("factory-control", ['factory' => $factory]);
            } else {
                return $this->render("factory-info", ['factory' => $factory]);
            }
            
        } else {
            return $this->_r("Invalid factory ID");
        }
    }    
    
    public function actionMarket()
    {
        return $this->render("market/index");
    }

    public function actionMarketFactories()
    {
        $searchModel = new FactoryAuctionSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            FactoryAuction::find()->where([
                'winner_unnp' => null,
            ])->andWhere([
                '>', 'date_end', time()
            ])->with('factory.holding')->with('lastBet.holding')
        );

        return $this->render('market/factories', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $this->getUser()
        ]);
    }
    
    public function actionMarketResurses()
    {
        $prototypes = ResurseProto::find()->all();
        
        return $this->render('market/resurses', [
            'prototypes' => $prototypes,
            'user' => $this->getUser()
        ]);
    }
    
}
