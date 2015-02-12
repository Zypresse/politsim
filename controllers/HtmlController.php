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

class HtmlController extends MyController
{
	public function actionCapital($uid = false)
	{
		if ($uid === false) $uid = $this->viewer_id;
		$uid = intval($uid);

		if ($uid) {
			$user = User::findByPk($uid);
            if (is_null($user)) 
                return $this->_r("User not found");

            $dealings = Dealing::getMyList($uid,$this->viewer_id);
            
            return $this->render("capital",['user'=>$user,'dealings'=>$dealings,'viewer_id'=>$this->viewer_id]);

		} else 
            return $this->_r("Invalid uid");
	}

	public function actionProfile($uid = false)
	{
		if ($uid === false) $uid = $this->viewer_id;
		$uid = intval($uid);

		if ($uid) {
			$user = User::findByPk($uid);
            if (is_null($user)) 
                return $this->_r("User not found");

            return $this->render("profile",['user'=>$user,'is_own'=>($this->viewer_id === $user->id)]);

		} else 
            return $this->_r("Invalid uid");
	}

	public function actionWork()
	{
		$user = User::findByPk($this->viewer_id);
		if ($user->post) {
			$bills = Bill::find()->where(['state_id'=>$user->state_id])->limit(5)->orderBy('vote_ended DESC')->all();
			
			return $this->render("work",['user'=>$user,'bills'=>$bills]);
		} else 
			return $this->_r("No works");
	}

	public function actionOrgInfo($id)
	{
		$id = intval($id);
		if ($id>0) {
			$org = Org::findByPk($id);
			if (is_null($org)) 
                return $this->_r("Organisation not found");

            return $this->render("org_info",['org'=>$org]);
		} else 
			return $this->_r("Invalid organisation ID");
	}

	public function actionMapPolitic()
	{
		$regions = Region::find()->all();
		
		return $this->render("map_politic",['regions'=>$regions]);
	}
	public function actionMapPopulation()
	{
		$regions = Region::find()->all();

		return $this->render("map_population",['regions'=>$regions]);
	}
	public function actionMapResurses()
	{
		$regions = Region::find()->all();
		$resurses = Resurse::find()->where(['level'=>0])->all();

		return $this->render("map_resurses",['regions'=>$regions,'resurses'=>$resurses]);
	}

	public function actionChartPeoples()
	{
		$users = User::find()->where('star > 0')->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();
		$user = User::findByPk($this->viewer_id);
		$r = $user->star + $user->heart/10 + $user->chart_pie/100;
		$place = User::find()->where('`star` + `heart`/10 + `chart_pie`/100 > '.$r)->count();

		return $this->render("chart_peoples",['users'=>$users,'user'=>$user,'place'=>$place]);
	}

	public function actionChartParties($state_id = false)
	{
		if ($state_id) {
			$state = State::findByPk($state_id);
			if (is_null($state))
				return $this->_r("State not found");
		
			$parties = Party::find()->where(['state_id'=>$state_id])->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();

			return $this->render("chart_parties",['parties'=>$parties,'state'=>$state]);
		} else {
			$parties = Party::find()->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC')->limit(100)->all();

			return $this->render("chart_parties",['parties'=>$parties,'state'=>false]);
		}
	}

	public function actionChartStates()
	{
		$states = State::find()->orderBy('population DESC')->all();

		return $this->render("chart_states",['states'=>$states]);
	}

	public function actionElections($state_id = true)
	{
		if (intval($state_id) > 0) {
			$user = User::findByPk($this->viewer_id);
			if ($state_id === true) {
				$state_id = $user->state_id;
				$state = $user->state;
			} else {
				$state_id = intval($state_id);
				$state = State::findByPk($state_id);
			}
			if (is_null($state))
				return $this->_r("State not found");

			return $this->render("elections",['state'=>$state,'user'=>$user]);

		} else
			return $this->_r("Invalid state ID");
	}

	public function actionStateInfo($id = false)
	{
		$user = User::findByPk($this->viewer_id);

		if ($id === false) {
			$id = $user->state_id;
		}
		$id = intval($id);

		if ($id>0) {
			$state = State::findByPk($id);
			if (is_null($state))
				return $this->_r("State not found");

			$ideologies = Ideology::find()->all();

			return $this->render("state_info",['state'=>$state,'ideologies'=>$ideologies,'user'=>$user]);

		} else
			return $this->_r("Invalid state ID");
	}

	public function actionPartyInfo($id = false)
	{
		$user = User::findByPk($this->viewer_id);

		if ($id === false) {
			$id = $user->party_id;
		}
		$id = intval($id);

		if ($id>0) {
			$party = Party::findByPk($id);
			if (is_null($party))
				return $this->_r("Party not found");

			return $this->render("party_info",['party'=>$party,'user'=>$user]);

		} else
			return $this->_r("Invalid party ID");
	}
}