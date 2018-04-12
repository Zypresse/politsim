<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\auth\RegistrationForm;
use app\models\auth\LoginForm;
use app\models\auth\Account;
use app\models\auth\AccountProvider;

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
		'class' => AuthAction::class,
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
		'class' => AccessControl::class,
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
		'class' => VerbFilter::class,
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
    
    /**
     * 
     * @param ClientInterface $client
     */
    public function onAuthSuccess(ClientInterface $client)
    {
        
        $sourceType = AccountProvider::typeToId($client->getId());
        $attributes = $client->getUserAttributes();
        $sourceId = (string) $attributes['id'] ?: $attributes['uid'];

        /* @var $accountProvider AccountProvider */
        $accountProvider = AccountProvider::findOne([
            'sourceType' => $sourceType,
            'sourceId' => $sourceId,
        ]);
        
        if (is_null($accountProvider)) {
            $email = $attributes['email'];
            $account = Account::findIdentityByEmail($email);
            if ($account) {
                $accountProvider = new AccountProvider([
                    'accountId' => $account->id,
                    'sourceType' => $sourceType,
                    'sourceId' => $sourceId,
                ]);
                $accountProvider->save();
            }
        } else {
            $account = $accountProvider->account;
        }
        
        if (Yii::$app->user->isGuest) {
            if ($account) { // login
                Yii::$app->user->login($account, 30*24*60*60);
            } else { // signup
                $params = AccountProvider::loadParams($sourceType, $attributes);
                $accountProvider = AccountProvider::signUp($sourceType, $params);
                if ($accountProvider->getErrors()) { // TODO
                    var_dump($accountProvider->getErrors()); die();
                }
            }
            return $this->redirect('/');
        } else { // user already logged in
            if (!$accountProvider) { // add auth provider
                $accountProvider = new AccountProvider([
                    'accountId' => Yii::$app->user->id,
                    'sourceType' => $sourceType,
                    'sourceId' => $sourceId,
                ]);
                $accountProvider->save();
            }
            return $this->redirect(['account/profile']);
        }
        

    }

}
