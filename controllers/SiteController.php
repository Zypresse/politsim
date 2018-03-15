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
	];
    }

    /**
     * Landing page
     * @return mixed
     */
    public function actionIndex()
    {
	$this->layout = 'landing';
	return $this->render('landing');
    }
    
}
