<?php

namespace app\models\auth;

use Yii;
use app\models\base\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "accounts".
 *
 * @property integer $id
 * @property string $email Email
 * @property string $password Password
 * @property string $accessToken Access token
 * @property integer $role Role ID
 * @property integer $status Status ID
 * @property integer $dateCreated Registration date
 * @property integer $dateExpected Date of next payment
 * @property integer $activeUserId Last selected (primary) person
 *
 * @property User[] $users
 * @property AccountProvider[] $providers
 *
 */
class Account extends ActiveRecord implements IdentityInterface
{
    
    const ROLE_USER = 1;
    const ROLE_ADMIN = 100;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreated',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'accessToken'], 'required'],
            [['role', 'status', 'dateCreated', 'dateExpected', 'activeUserId'], 'integer'],
            [['email'], 'string', 'max' => 256],
            [['password'], 'string', 'max' => 512],
            [['accessToken'], 'string', 'max' => 255],
            [['accessToken'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'accessToken' => 'Авторизационный ключ',
            'role' => 'Роль',
            'status' => 'Статус',
            'dateCreated' => 'Дата регистрации',
            'dateExpected' => 'Дата следующего платежа',
            'activeUserId' => 'Активный персонаж',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['accountId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProviders()
    {
        return $this->hasMany(AccountProvider::className(), ['accountId' => 'id']);
    }

    public function getAuthKey(): string
    {
        return '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey): bool
    {
        return false;
    }

    public static function findIdentity($id): IdentityInterface
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface
    {
        return null;
    }

}
