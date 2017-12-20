<?php

namespace app\models\auth;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

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
 *
 */
class Account extends ActiveRecord
{

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

}
