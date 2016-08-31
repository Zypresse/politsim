<?php

namespace app\controllers;

use Yii,
    yii\web\Controller,
    yii\filters\AccessControl,
    yii\filters\VerbFilter,
    yii\web\UploadedFile,
    app\models\Account,
    app\models\User,
    app\models\InviteForm,
    app\components\vkapi\VkApi;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\MyErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }
    
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
    
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'fullpage';
            return $this->render('homepage');
        } else {            
            if (Yii::$app->user->identity->isInvited) {
                return $this->render('index');
            } else {
                return $this->redirect(["invite"]);
            }
        }
    }
    
    public function actionVkAppAuth($viewer_id, $auth_key, $user_id)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }
        
//        $friends = json_decode(Yii::$app->request->get("api_result"))["response"];
        $real_key = md5(Yii::$app->params['VK_APP_ID']."_".$viewer_id."_".Yii::$app->params['VK_APP_KEY']);
        
        if ($real_key !== $auth_key) {
            exit('Invalid auth key!');
        }
        $VK = new VkApi(Yii::$app->params['VK_APP_ID'],Yii::$app->params['VK_APP_KEY']);
       
//        $isMember = $VK->api('groups.isMember', ['group_id'=>'politsim','user_id'=>$viewer_id]);
//        if (!$isMember['response']) exit('Игра доступна только для альфа-тестеров.');
        
        $vkinfo = $VK->api('users.get', array('https'=>1,'user_ids'=>$viewer_id,'fields'=>'sex,photo_50,photo_big','version'=>'5.40'));
        
        if (!(isset($vkinfo['response'][0]['first_name']))) exit('VK API error');
        $vkinfo = $vkinfo['response'][0];
        
        $account = Account::find()->where([
            'sourceType' => 3,
            'sourceId' => $viewer_id,
        ])->one();
                
        if ($account) { // login
            /* @var $user User */
            $user = $account->user;
            Account::updateUserInfo($user, 3, $vkinfo, true);
            
            Yii::$app->user->login($user, 30*24*60*60);
                
            if ($user->isInvited) {
                $accountView = Account::find()->where([
                    'sourceType' => 3,
                    'sourceId' => $user_id,
                ])->one();
                
                if ($accountView && $accountView->user && $accountView->user->id) {
                    $user_id = $accountView->user->id;
                } else {
                    $user_id = $account->user->id;
                }
                
                $this->redirect("/#!profile&id={$user_id}");
            } else {
                $this->redirect("invite");
            }
        } else { // signup
            $account = Account::signUp(3, $vkinfo);
        }
        
        if (!$account->id) {
            var_dump($account->getErrors());
        }
    }
    
    public function onAuthSuccess($client)
    {
        
        $clients = [
            'google' => 1,
            'facebook' => 2,
            'vkontakte' => 3,
            'vkapp' => 3
        ];
        $sourceType = $clients[$client->getId()];
        $attributes = $client->getUserAttributes();
        $sourceId = (string)(isset($attributes['id'])?$attributes['id']:$attributes['uid']);

        /* @var $account Account */
        $account = Account::find()->where([
            'sourceType' => $sourceType,
            'sourceId' => $sourceId,
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($account && $account->user) { // login
                Account::updateUserInfo($account->user, $sourceType, $attributes, true);
                Yii::$app->user->login($account->user, 30*24*60*60);
            } else { // signup
                $account = Account::signUp($sourceType, $attributes);
            }

            if ($account->user->isInvited) {
                $this->redirect("/");
            } else {
                $this->redirect("invite");
            }
        } else { // user already logged in
            if (!$account) { // add auth provider
                $account = new Account([
                    'userId' => Yii::$app->user->id,
                    'sourceType' => $sourceType,
                    'sourceId' => $sourceId,
                ]);
                $account->save();
            }
        }

    }

    public function actionInvite()
    {
        $this->layout = 'fullpage';
        if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isInvited) {
            $model = new InviteForm();

            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->validate()) {
                    $invite = $model->getInvite();
                    if ($invite) {
                        $invite->activateUser(Yii::$app->user->identity);
                        $this->redirect('/');
                    } else {
                        $model->addError('imageFile', Yii::t('app', 'Invalid invite'));
                    }
                }
            }

            return $this->render('invite', ['model' => $model]);
        } else {
            $this->redirect('/');
        }
    }
        
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
}
