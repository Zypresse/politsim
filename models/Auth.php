<?php

namespace app\models;

use Yii,
    app\components\MyModel,
    app\models\User;

/*
 * 
 * ,
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => '95701989043-8dh4etcl3dheudfs156mg131ftd0pbpv.apps.googleusercontent.com',
                    'clientSecret' => 'oxmR-jiruIs0Qi0aLbFu3fuq',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '403421213132291',
                    'clientSecret' => '23215c743e6e5e6c408d4249402f5dc5',
                ],
                'vk' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => 'vkontakte_client_id',
                    'clientSecret' => 'vkontakte_client_secret',
                ],
            ],
        ]
 * 
 * 
 */

/**
 * This is the model class for table "auth".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $source
 * @property string $source_id
 * 
 * @property User $user
 */
class Auth extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id'], 'integer'],
            [['source', 'source_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'source' => 'Source',
            'source_id' => 'Source ID',
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }
    
    /**
     * 
     * @param string $source
     * @param array $attributes
     * @return Auth|User
     */
    public static function signUp($source, $attributes)
    {
        switch ($source) {
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
            case 'vkapp':
                $user = new User([
                    'name' => $attributes['first_name'] . ' ' . $attributes['last_name'],
                    'sex' => intval($attributes['sex']),
                    'photo' => $attributes['photo_50'],
                    'photo_big' => (isset($attributes['photo_400_orig'])) ? $attributes['photo_400_orig'] : $attributes['photo_big'],
                    'money' => 100000                    
                ]);
                break;

        }
        
        $user->party_id = 0;
        $user->state_id = 0;
        $user->post_id = 0;
        $user->region_id = 0;
        
        $transaction = $user->getDb()->beginTransaction();
        if ($user->save()) {
            $auth = new Auth([
                'user_id' => $user->id,
                'source' => $source,
                'source_id' => (string)(isset($attributes['id'])?$attributes['id']:$attributes['uid']),
            ]);
            if ($auth->save()) {
                $transaction->commit();
                Yii::$app->user->login($user);
            } else {
//                print_r($auth->getErrors());
            }
            return $auth;
        } else {
//            print_r($user->getErrors());
            return $user;
        }
    }
}
