<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
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
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['account/profile', 'id' => Yii::$app->user->id]);
        }
        
	$this->layout = 'landing';
	return $this->render('landing');
    }
    
}
