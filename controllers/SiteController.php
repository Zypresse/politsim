<?php

namespace app\controllers;

use yii\web\Controller;
use yii\authclient\AuthAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\ErrorAction;

/**
 * Main controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
	return [
	    'error' => [
		'class' => ErrorAction::className(),
	    ],
	    'auth' => [
		'class' => AuthAction::className(),
		'successCallback' => [$this, 'onAuthSuccess'],
	    ],
	];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
	return [
	    'access' => [
		'class' => AccessControl::className(),
		'only' => ['logout', 'register', 'login'],
		'rules' => [
		    [
			'actions' => ['logout'],
			'allow' => true,
			'roles' => ['@'],
		    ],
		    [
			'actions' => ['login', 'register'],
			'allow' => true,
			'roles' => ['?'],
		    ],
		],
	    ],
	    'verbs' => [
		'class' => VerbFilter::className(),
		'actions' => [
		    'logout' => ['post'],
		],
	    ],
	];
    }

    /**
     * Landing page
     * @return string
     */
    public function actionIndex()
    {
	$this->layout = 'landing';
	return $this->render('landing');
    }

    /**
     * Registration page
     * @return string
     */
    public function actionRegister()
    {
	return $this->render('register');
    }

    /**
     * Log out
     * @return string
     */
    public function actionLogout()
    {
	Yii::$app->user->logout();
	return $this->goHome();
    }

}
