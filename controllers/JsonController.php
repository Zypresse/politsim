<?php

namespace app\controllers;

use yii;
use yii\base\ViewContextInterface;
use yii\web\Controller;
use app\models\User;

class JsonController extends Controller implements ViewContextInterface
{
    private $result = 'undefined';
    private $error = false;
    private function _r() 
    {
        if ($this->error) $this->result = 'error';
        return $this->render('json',['result'=>$this->result,'error'=>$this->error]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\JsonErrorAction',
            ],
        ];
    }
	public $layout = 'api';
	public function getViewPath()
	{
	    return Yii::getAlias('@app/views');
	}

    public function actionHello()
    {
        $this->result = 'Hello, world!';
        return $this->_r(); 
    }

    public function actionUserinfo($uid = false, $nick = false)
    {
        if ($uid === false && $nick === false) {
            $this->error = 'Invalid params';
        } else {
            if ($uid) {
                $uid = intval($uid);
                $user = User::findByPk($uid);
            } else {
                $nick = str_replace("@", "", mb_strtolower($nick));
                $user = User::find()->where(["twitter_nickname"=>$nick])->one();
            }
            if (is_null($user)) {
                $this->error = 'User not found';
            } else {
                $this->result = $user->getPublicAttributes();
            }
        }
        return $this->_r();
    }

    public function actionGovermentfieldtype_info($id)
    {
        return $this->_r();
    }

}
