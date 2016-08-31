<?php

namespace app\models;

use Yii,
    yii\web\IdentityInterface,
    app\components\TaxPayer,
    app\components\MyModel,
    app\models\Ideology;

/**
 * Пользователь игры. Таблица "users".
 *
 * @property integer $id 
 * @property string $name Имя
 * @property string $avatar Маленькая фотография 50x50
 * @property string $avatarBig Большая фотография 400xn
 * @property integer $genderId Пол: 0 - неопр., 1 - женский, 2 - мужской
 * @property integer $cityId ID Города
 * @property integer $ideologyId ID Идеологии
 * @property integer $religionId ID Религии
 * @property integer $fame Известность
 * @property integer $trust Доверие
 * @property integer $success Успешность
 * @property integer $fameBase Известность базовая
 * @property integer $trustBase Доверие базовая
 * @property integer $successBase Успешность базовая * 
 * @property integer $dateCreated Дата регистрации
 * @property integer $dateLastLogin Дата последнего входа
 * @property boolean $isInvited Флаг есть ли инвайт у юзера
 * @property integer $utr ИНН
 * 
 * @property string $authKey Авторизационный ключ
 * 
 * @property State[] $states Государство
 * @property Citizenship[] $citizenships Гражданства
 * @property Party[] $parties Партии
 * @property Post[] $posts Посты
 * @property Notification[] $notifications Уведомления
 * @property Building[] $buildings Здания которыми он управляет
 * @property BuildingTwotiled[] $buildingsTwotiled Двухтайловые здания которыми он управляет
 * @property Unit[] $units Движимые организации которыми он управляет
 * @property Massmedia[] $massmedias Массмедиа которыми он управляет
 * @property Company[] $companies Компании, директором которых является
 * @property Account[] $accounts Аккаунты
 * @property Ideology $ideology Идеология
 * @property Religion $religion Религия
 * @property City $city Город
 */
class User extends MyModel implements TaxPayer, IdentityInterface
{

    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return Utr::TYPE_USER;
    }
    
    /**
     * Возвращает ИНН
     * @return int
     */
    public function getUtr()
    {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['objectId' => $this->id, 'objectType' => $this->getUtrType()]);
            if ($u) {
                $this->utr = $u->id;
                $this->save();
            }
        } 
        return $this->utr;
    }
    
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment($stateId)
    {
        return false;
    }
    
    /**
     * Возвращает баланс плательщика в у.е.
     * @param integer $currencyId
     * @return double
     */
    public function getBalance($currencyId)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()            
        ], false, [
            'count' => 0
        ]);
        return $money->count;
    }
    
    /**
     * Меняет баланс плательщика
     * @param integer $currencyId
     * @param double $delta
     */
    public function changeBalance($currencyId, $delta)
    {
        $money = resources\Resource::findOrCreate([
            'protoId' => 1, // деньги
            'subProtoId' => $currencyId,
            'masterId' => $this->getUtr()     
        ], false, [
            'count' => 0
        ]);
        $money->count += $delta;
        return $money->save();
    }
        
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return $this->location->getTaxStateId();
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId)
    {
        return $this->location->isTaxedInState($stateId);
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return $this->id;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController($userId)
    {
        return $this->id === $userId;
    }    

    public static function findIdentity($id)
    {
        return static::findByPk($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
        return $authKey == $this->getAuthKey();
    }

    const SEX_UNDEFINED = 0;
    const SEX_FEMALE = 1;
    const SEX_MALE = 2;

    public static function stringGenderToSex($gender)
    {
        switch ($gender) {
            case 'male':
                return static::SEX_MALE;
            case 'female':
                return static::SEX_FEMALE;
            default:
                return static::SEX_UNDEFINED;
        }
    }

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
    public function rules()
    {
        return [
            [['name', 'avatar', 'avatarBig', 'genderId', 'cityId', 'dateLastLogin'], 'required'],
            [['genderId'], 'integer', 'min' => 0, 'max' => 2],
            [['cityId', 'ideologyId', 'religionId', 'dateCreated', 'dateLastLogin', 'utr'], 'integer', 'min' => 0],
            [['fame', 'trust', 'success', 'fameBase', 'trustBase', 'successBase'], 'integer'],
            [['fame', 'trust', 'success', 'fameBase', 'trustBase', 'successBase'], 'default', 'value' => 0],
            [['name'], 'string', 'max' => 255],
            [['avatar', 'avatarBig'], 'string'],
            [['isInvited'], 'boolean'],
            [['isInvited'], 'default', 'value' => false],
        ];
    }

    public function setPublicAttributes()
    {
        return [
            'id',
            'name',
            'avatar',
            'avatarBig',
            'gender',
            'fame',
            'trust',
            'success',
        ];
    }

    public function getAuthKey()
    {
        return static::getRealKey($this->id);
    }

    public static function getRealKey($id)
    {
        return md5($id . Yii::$app->params['AUTH_KEY_SECRET']);
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->dateCreated = time();
        }
        return parent::beforeSave($insert);
    }
    
    public function updateLastLogin($save = false)
    {
        $this->dateLastLogin = time();
        if ($save) {
            return $this->save();
        }
    }
    
    private $_ideology = null;
    public function getIdeology()
    {
        if (is_null($this->_ideology) && $this->ideologyId) {
            $this->_ideology = Ideology::findOne($this->ideologyId);
        }
        return $this->_ideology;
    }
    
    private $_religion = null;
    public function getReligion()
    {
        if (is_null($this->_religion) && $this->religionId) {
            $this->_religion = Religion::findOne($this->religionId);
        }
        return $this->_religion;
    }
    
    public function getParties()
    {
        return [];
    }
    
    public function getStates()
    {
        return [];
    }
    
    public function getPosts()
    {
        return [];
    }
}
