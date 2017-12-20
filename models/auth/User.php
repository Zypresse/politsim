<?php

namespace app\models\auth;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property integer $accountId Account ID
 * @property string $name Name
 * @property string $avatar Avatar 50x50
 * @property string $avatarBig Avatar with 300px width
 * @property integer $gender Gender
 * @property integer $tileId Location tile ID
 * @property integer $ideologyId Ideology ID
 * @property integer $fame Fame
 * @property integer $trust Trust
 * @property integer $success Success
 * @property integer $fameBase Base fame
 * @property integer $trustBase Base trust
 * @property integer $successBase Base success
 * @property integer $dateCreated Registration date
 * @property integer $utr UTR
 *
 * @property Account $account
 */
class User extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
	return 'users';

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
	    [['accountId', 'name'], 'required'],
	    [['accountId', 'gender', 'tileId', 'ideologyId', 'fame', 'trust', 'success', 'fameBase', 'trustBase', 'successBase', 'dateCreated', 'utr'], 'integer'],
	    [['avatar', 'avatarBig'], 'string'],
	    [['name'], 'string', 'max' => 255],
	    [['utr'], 'unique'],
	    [['accountId'], 'exist', 'skipOnError' => false, 'targetClass' => Account::className(), 'targetAttribute' => ['accountId' => 'id']],
	];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
	return [
	    'id' => 'ID',
	    'accountId' => 'Аккаунт',
	    'name' => 'Имя',
	    'avatar' => 'Аватар',
	    'avatarBig' => 'Аватар (большой)',
	    'gender' => 'Пол',
	    'tileId' => 'Место жительства',
	    'ideologyId' => 'Идеология',
	    'fame' => 'Известность',
	    'trust' => 'Доверие',
	    'success' => 'Успешность',
	    'fameBase' => 'Базовая известность',
	    'trustBase' => 'Базовое доверие',
	    'successBase' => 'Базовая успешность',
	    'dateCreated' => 'Дата регистрации',
	    'utr' => 'ИНН',
	];

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
	return $this->hasOne(Account::className(), ['id' => 'accountId']);

    }

}
