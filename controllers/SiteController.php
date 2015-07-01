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
                
                switch ($client->getId()) {
                    case 'google':
                        $user = new User([
                            'name' => $attributes['displayName'],
                            'sex' => User::stringGenderToSex($attributes['gender']),
                            'photo' => $attributes['image']['url'],
                            'photo_big' => preg_replace("/sz=50/", "/sz=400", $attributes['image']['url']),
                            'money' => 200000                    
                        ]);
                        break;
                    case 'facebook':
                        $user = new User([
                            'name' => $attributes['name'],
                            'sex' => User::stringGenderToSex($attributes['gender']),
                            'photo' => "http://graph.facebook.com/{$attributes['id']}/picture",
                            'photo_big' => "http://graph.facebook.com/{$attributes['id']}/picture?width=400&height=800",
                            'money' => 100000                    
                        ]);
                        break;
                    case 'vkontakte':
                        $user = new User([
                            'name' => $attributes['first_name'] . ' ' . $attributes['last_name'],
                            'sex' => $attributes['sex'],
                            'photo' => $attributes['photo_50'],
                            'photo_big' => $attributes['photo_400_orig'],
                            'money' => 100000                    
                        ]);
                        break;
                        
                }
                $transaction = $user->getDb()->beginTransaction();
                if ($user->save()) {
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string)$attributes['id'],
                    ]);
                    if ($auth->save()) {
                        $transaction->commit();
                        Yii::$app->user->login($user);
                    } else {
                        print_r($auth->getErrors());
                    }
                } else {
                    print_r($user->getErrors());
                }
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
    }

}
