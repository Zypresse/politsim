<?php

namespace app\models\auth;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\base\taxpayers\TaxPayerInterface;
use app\models\base\ActiveRecord;
use app\models\economy\UtrType;
use yii\web\UploadedFile;
use yii\imagine\Image;
use app\models\government\Citizenship;
use app\models\government\State;

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
 * @property Citizenship $citizenships
 * @property State[] $states
 */
class User extends ActiveRecord implements TaxPayerInterface
{
    
    use \app\models\base\taxpayers\TaxPayerTrait;
    
    const GENDER_UNKNOWN = 0;
    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    
    /**
     *
     * @var \yii\web\UploadedFile
     */
    public $avatarFile;

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
            [['avatarFile'], 'file'],
            [['avatarFile'], 'safe'],
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
	    'name' => 'Полное имя',
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
            'avatarFile' => 'Фотография',
	];
    }
    
    /**
     * @return array
     */
    public static function gendersList()
    {
        return [
            self::GENDER_MALE => 'Мужской',
            self::GENDER_FEMALE => 'Женский',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
	return $this->hasOne(Account::className(), ['id' => 'accountId']);
    }

    /**
     * @return integer Taxed in state
     */
    public function getTaxStateId(): int
    {
	return null;
    }

    /**
     * @return integer ID of controlling user
     */
    public function getUserControllerId(): int
    {
	return $this->id;
    }

    /**
     * @return integer Tax payer type
     */
    public function getUtrType(): int
    {
	return UtrType::USER;
    }

    /**
     * Check tax payer is government of state
     * @param integer $stateId
     * @return boolean
     */
    public function isGovernment(int $stateId): bool
    {
	return false;
    }

    /**
     * Check tax payer is payed of state
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState(int $stateId): bool
    {
	return false;
    }

    /**
     * Check this user can control this tax payer
     * @param integer $userId
     */
    public function isUserController(int $userId): bool
    {
	return $this->id === $userId;
    }
    
    public function saveAvatar()
    {
        $this->avatarFile = UploadedFile::getInstance($this, 'avatarFile');
        if ($this->avatarFile->error) {
            $this->addError('avatarFile', 'Error #'.$this->avatarFile->error);
            return false;
        }
        
        $path = Yii::getAlias("@webroot/upload/avatars/{$this->id}/");
        if (!is_dir($path)) {
            mkdir($path);
        }
        
        Image::thumbnail($this->avatarFile->tempName , 50, 50)->save($path.'50.jpg', ['quality' => 80]);
        Image::resize($this->avatarFile->tempName, 300, null, true)->save($path.'300.jpg', ['quality' => 80]);
        
        $this->avatarBig = '/upload/avatars/'.$this->id.'/300.jpg';
        $this->avatar = '/upload/avatars/'.$this->id.'/50.jpg';
        return $this->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitizenships()
    {
        return $this->hasMany(Citizenship::class, ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(State::class, ['id' => 'stateId'])->viaTable('citizenships', ['userId' => 'id']);
    }
    
}
