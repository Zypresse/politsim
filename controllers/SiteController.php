<?php

namespace app\controllers;

use Yii,
    yii\web\Controller,
    yii\web\UploadedFile,
    app\models\Auth,
    app\models\InviteForm;

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
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->invited) {
            return $this->render('index');
        } else {
            return $this->redirect(["invite"]);
        }
    }
    
    public function actionVkAppAuth($viewer_id, $auth_key)
    {
//        $friends = json_decode(Yii::$app->request->get("api_result"))["response"];
        $real_key = md5(Yii::$app->params['VK_APP_ID']."_".$viewer_id."_".Yii::$app->params['VK_APP_KEY']);
        
        if ($real_key !== $auth_key) exit('Invalid auth key');
        
        $VK = new \app\components\vkapi\VkApi(Yii::$app->params['VK_APP_ID'],Yii::$app->params['VK_APP_KEY']);
       
//        $isMember = $VK->api('groups.isMember', ['group_id'=>'politsim','user_id'=>$viewer_id]);
//        if (!$isMember['response']) exit('Игра доступна только для альфа-тестеров.');
        
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
            if (!$user->died) {
                Yii::$app->user->login($user, 30*24*60*60);
            } else {
                exit("banned");
            }
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
            if ($auth && $auth->user) { // login
                
                Yii::$app->user->login($auth->user, 30*24*60*60);
                if ($auth->user->invited) {
                    $this->redirect("/");
                } else {
                    $this->redirect("invite");
                }
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

    public function actionInvite()
    {
        
        if (!Yii::$app->user->isGuest) {
        
            $model = new InviteForm();

            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->validate()) {
                    $invite = $model->getInvite();
                    if ($invite) {
                        $invite->uid = Yii::$app->user->id;
                        $invite->time = time();
                        $invite->save();
                        Yii::$app->user->identity->invited = 1;
                        Yii::$app->user->identity->save();
                        $this->redirect("/");
                    } else {
                        $model->addError('imageFile', 'Invalid invite');
                    }
                }
            }

            return $this->render('invite', ['model' => $model]);
        } else {
            $this->redirect("/");
        }
    }
}
