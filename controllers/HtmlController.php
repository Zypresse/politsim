<?php

namespace app\controllers;

use yii;
use app\models\User;
use app\models\Dealing;
use app\models\Medale;
use app\models\Bill;
use app\components\MyController;
use yii\helpers\ArrayHelper;

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
			
			return $this->render("work",["user"=>$user,"bills"=>$bills]);
		} else {
			return $this->_r("No works");
		}
	}
}