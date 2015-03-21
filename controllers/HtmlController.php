<?php

namespace app\controllers;

use yii;
use app\components\MyController;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Dealing;
use app\models\Medale;
use app\models\Bill;
use app\models\Org;
use app\models\Region;
use app\models\Resurse;
use app\models\Party;
use app\models\State;
use app\models\Ideology;
use app\models\Twitter;
use app\models\Holding;
use app\models\Stock;

class HtmlController extends MyController {

    public function actionCapital($uid = false) {
        if ($uid === false)
            $uid = $this->viewer_id;
        $uid = intval($uid);

        if ($uid) {
            $user = User::findByPk($uid);
            if (is_null($user))
                return $this->_r("User not found");

            $dealings = Dealing::getMyList($uid, $this->viewer_id);

            return $this->render("capital", ['user' => $user, 'dealings' => $dealings, 'viewer_id' => $this->viewer_id]);
        } else
            return $this->_r("Invalid uid");
    }

    public function actionProfile($uid = false) {
        if ($uid === false)
            $uid = $this->viewer_id;
        $uid = intval($uid);

        if ($uid) {
            $user = User::findByPk($uid);
            if (is_null($user))
                return $this->_r("User not found");

            return $this->render("profile", ['user' => $user, 'is_own' => ($this->viewer_id === $user->id), 'viewer' => ($this->viewer_id === $user->id) ? $user : $this->getUser()]);
        } else
            return $this->_r("Invalid uid");
    }

    public function actionWork() {
        $user = User::findByPk($this->viewer_id);
        if ($user->post) {
            return $this->render("work", ['user' => $user]);
        } else
            return $this->render("not-have-work", ['user' => $user]);
    }

    public function actionOrgInfo($id) {
        $id = intval($id);
        if ($id > 0) {
            $org = Org::findByPk($id);
            if (is_null($org))
                return $this->_r("Organisation not found");

            return $this->render("org_info", ['org' => $org]);
        } else
            return $this->_r("Invalid organisation ID");
    }

    public function actionMapPolitic() {
        $regions = Region::find()->all();

        return $this->render("map_politic", ['regions' => $regions]);
    }
    public function actionMapCores() {
        $regions = Region::find()->all();

        return $this->render("map_cores", ['regions' => $regions]);
    }

    public function actionMapPopulation() {
        $regions = Region::find()->all();

        return $this->render("map_population", ['regions' => $regions]);
    }

    public function actionMapResurses() {
        $regions = Region::find()->all();
        $resurses = Resurse::find()->where(['level' => 0])->all();

        return $this->render("map_resurses", ['regions' => $regions, 'resurses' => $resurses]);
    }

    public function actionChartPeoples() {
        $users = User::find()->where('star > 0')->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();
        $user = User::findByPk($this->viewer_id);
        $r = $user->star + $user->heart / 10 + $user->chart_pie / 100;
        $place = User::find()->where('`star` + `heart`/10 + `chart_pie`/100 > ' . $r)->count() + 1;

        return $this->render("chart_peoples", ['users' => $users, 'user' => $user, 'place' => $place]);
    }

    public function actionChartParties($state_id = false) {
        if ($state_id) {
            $state = State::findByPk($state_id);
            if (is_null($state))
                return $this->_r("State not found");

            $parties = Party::find()->where(['state_id' => $state_id])->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();

            return $this->render("chart_parties", ['parties' => $parties, 'state' => $state]);
        } else {
            $parties = Party::find()->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();

            return $this->render("chart_parties", ['parties' => $parties, 'state' => false]);
        }
    }

    public function actionChartStates() {
        $states = State::find()->orderBy('population DESC')->all();

        return $this->render("chart_states", ['states' => $states]);
    }
    
    public function actionChartHoldings() {
        $holdings = Holding::find()->orderBy('capital DESC')->all();

        return $this->render("chart_holdings", ['holdings' => $holdings]);
    }

    public function actionElections($state_id = false) {
        $user = User::findByPk($this->viewer_id);
        if ($state_id === false) {
                if ($user->state_id) {
                    $state_id = $user->state_id;
                } else {
                    return $this->render("not-have-state",['user'=>$user]);
                }
        }
        if (intval($state_id) > 0) {
            
            if ($state_id === $user->state_id) {
                $state = $user->state;
            } else {
                $state = State::findByPk($state_id);
            }
            if (is_null($state))
                return $this->_r("State not found");

            return $this->render("elections", ['state' => $state, 'user' => $user]);
        } else
            return $this->_r("Invalid state ID");
    }

    public function actionStateInfo($id = false) {
        $user = User::findByPk($this->viewer_id);

        if ($id === false) {
            if ($user->state_id) {
                $id = $user->state_id;
            } else {
                return $this->render("not-have-state",['user'=>$user]);
            }
        }
        $id = intval($id);

        if ($id > 0) {
            $state = State::findByPk($id);
            if (is_null($state))
                return $this->_r("State not found");

            $ideologies = Ideology::find()->all();

            return $this->render("state_info", ['state' => $state, 'ideologies' => $ideologies, 'user' => $user]);
        } else
            return $this->_r("Invalid state ID");
    }

    public function actionPartyInfo($id = false) {
        $user = User::findByPk($this->viewer_id);

        if ($id === false) {
            
            if ($user->party_id) {
                $id = $user->party_id;
            } else {
                return $this->render("not-have-party",['user'=>$user]);
            }
        }
        $id = intval($id);

        if ($id > 0) {
            $party = Party::findByPk($id);
            if (is_null($party))
                return $this->_r("Party not found");

            return $this->render("party_info", ['party' => $party, 'user' => $user]);
        } else
            return $this->_r("Invalid party ID");
    }

    public function actionTwitter($uid = false, $nick = false, $tag = false) {
        $uid = ($uid === false ? $this->viewer_id : intval($uid));
        if ($tag) {

            mb_internal_encoding('UTF-8');
            $tag = preg_replace("`[^A-Za-zАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9_\-]+`u", '', $tag);

            $time = time();
            //var_dump(Twitter::find()->where(['like','text','%#'.$tag.'%'])->limit(4)->orderBy('date DESC')->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
            $tweets = Twitter::find()->where('original = 0 AND text LIKE :query')->addParams([':query'=>'%#'.$tag.'%'])->limit(4)->orderBy('date DESC')->all();
            $feed = Twitter::find()->where("retweets > 0 AND date <= " . $time)->limit(5)->orderBy('date DESC')->all();
            
            return $this->render("twitter-feed", ['tag' => $tag, 'viewer_id' => $this->viewer_id, 'timeFeedGenerated' => $time, 'tweets' => $tweets, 'feed' => $feed]);
        } else {
            if ($nick) {
                $user = User::find()->where(['twitter_nickname' => $nick])->one();
            } else {
                $user = User::findByPk($uid);
            }
            if (is_null($user))
                return $this->_r("User not found");

            $time = time();
            $tweets = Twitter::find()->where(["uid" => $user->id])->limit(3)->orderBy('date DESC')->all();
            $feed = Twitter::find()->where("retweets > 0 AND date <= " . $time)->limit(5)->orderBy('date DESC')->all();

            return $this->render("twitter", ['viewer_id' => $this->viewer_id, 'timeFeedGenerated' => $time, 'user' => $user, 'tweets' => $tweets, 'feed' => $feed]);
        }
    }
    
    public function actionHoldingInfo($id)
    {
        $id = intval($id);
        if ($id) {
            $holding = Holding::findByPk($id);
            if (is_null($holding))
                return $this->_r("Holding not found");
            
            return $this->render("holding-info",['holding'=>$holding,'user'=>$this->getUser()]);
        } else
            return $this->_r("Invalid holding ID");
    }
    
    public function actionMyBuisness()
    {
        $user = $this->getUser();
        
        return $this->render("my-buisness",['user'=>$user]);
    }
    
    public function actionDealings() {
        
        $user = $this->getUser();
        
        return $this->render("dealings", ['user' => $user]);
    }
    
    public function actionHoldingControl($id) {
        $id = intval($id);
        if ($id) {
            $holding = Holding::findByPk($id);
            if (is_null($holding))
                return $this->_r("Holding not found");
            
            $user = $this->getUser();
            
            if ($user->isShareholder($holding))            
                return $this->render("holding-control",['holding'=>$holding,'user'=>$user]);
            else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid holding ID");
    }
    
    public function actionNotifications() {
        return $this->render("notifications",['user'=>$this->getUser()]);
    }

}
