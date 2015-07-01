<?php

namespace app\models;

use Yii;

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
 */
class Auth extends \yii\db\ActiveRecord
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
        return $this->hasOne('app\models\User', array('id' => 'user_id'));
    }
}
