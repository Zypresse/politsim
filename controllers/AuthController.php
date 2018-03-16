<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\authclient\AuthAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\auth\RegistrationForm;

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
     * Login page
     * @return mixed
     */
    public function actionLogin()
    {
	return $this->render('login');
    }

    /**
     * Registration page
     * @return mixed
     */
    public function actionRegister()
    {
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
