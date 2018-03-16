<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\authclient\AuthAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\auth\RegistrationForm;
use app\models\auth\LoginForm;

/**
 * Авторизация и аутентификация
 *
 * @author ilya
 */
class AuthController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
	return [
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
     * Login page
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['account/profile', 'id' => Yii::$app->user->id]);
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->user->login($model->identity);
            return $this->redirect(['account/profile', 'id' => $model->identity->id]);
        }
	return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Registration page
     * @return mixed
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['account/profile', 'id' => Yii::$app->user->id]);
        }
        
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->user->login($model->identity);
            return $this->redirect(['account/profile', 'id' => $model->identity->id]);
        }
	return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Log out
     * @return mixed
     */
    public function actionLogout()
    {
	Yii::$app->user->logout();
	return $this->goHome();
    }

}
