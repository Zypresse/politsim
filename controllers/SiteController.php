<?php

namespace app\controllers;

use Yii,
    yii\web\Controller,
    app\models\User,
    app\models\Auth;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionVkAppAuth($viewer_id, $auth_key)
    {
        $real_key = md5(Yii::$app->params['VK_APP_ID']."_".$viewer_id."_".Yii::$app->params['VK_APP_KEY']);
        
        if ($real_key !== $auth_key) exit('Invalid auth key');
        
        $VK = new \app\components\vkapi\VkApi(Yii::$app->params['VK_APP_ID'],Yii::$app->params['VK_APP_KEY']);
       
        $isMember = $VK->api('groups.isMember', ['group_id'=>'politsim','user_id'=>$viewer_id]);
        if (!$isMember['response']) exit('Игра доступна только для альфа-тестеров.');
        
        $vkinfo = $VK->api('users.get', array('https'=>1,'user_ids'=>$viewer_id,'fields'=>'sex,photo_50,photo_400_orig,photo_big','v'=>'5.34'));
        
        if (!(isset($vkinfo['response'][0]['first_name']))) exit('VK API error');
        $vkinfo = $vkinfo['response'][0];
        
        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => 'vkontakte',
            'source_id' => $viewer_id,
        ])->one();
        
        if ($auth) { // login
            $user = $auth->user;
            Yii::$app->user->login($user);
        } else { // signup
            $auth = Auth::signUp('vkontakte', $vkinfo);
        }
        
        if ($auth->id) {
            $this->redirect("/");
        } else {
            var_dump($auth->getErrors());
        }
    }
    
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // signup
                Auth::signUp($client->getId(), $attributes);
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }

        // Visitor::findOneOrCreate(['ip'=>Yii::$app->request->userIP,'useragent'=>Yii::$app->request->userAgent,'user_id'=>Yii::$app->user->id]);

    }

}
