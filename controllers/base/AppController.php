<?php

namespace app\controllers\base;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Description of AppController
 *
 * @author ilya
 * @property \app\models\auth\User $user
 */
class AppController extends Controller
{
    
    public $layout = 'app';
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	return [
	    'access' => [
		'class' => AccessControl::className(),
		'rules' => [
		    [
			'allow' => true,
			'roles' => ['@'],
		    ],
		],
	    ],
	];
    }
    
    /**
     * @return \app\models\auth\User
     */
    protected function getUser()
    {
        return Yii::$app->user->identity->currentUser;
    }
    
    /**
     * 
     * @return mixed
     */
    protected function ok()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => 'ok'];
    }
    
    /**
     * 
     * @param mixed $e
     * @return mixed
     */
    protected function error($e)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => 'error', 'error' => $e];
    }
    
}
