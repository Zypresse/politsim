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
		'only' => ['logout'],
		'rules' => [
		    [
			'actions' => ['logout'],
			'allow' => true,
			'roles' => ['@'],
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
	return $this->render('landing');
    }

}
