<?php

namespace app\models;

use Yii,
    yii\web\IdentityInterface,
    app\models\economics\UtrType,
    app\models\economics\TaxPayerModel,
    app\models\account\Account,
    app\models\politics\State,
    app\models\politics\Citizenship,
    app\models\politics\Party,
    app\models\politics\AgencyPost,
    app\models\politics\PartyPost,
    app\models\politics\Membership,
    app\models\economics\Dealing,
    app\models\Tile;

/**
 * Пользователь игры. Таблица "users".
 *
 * @property integer $id 
 * @property string $name Имя
 * @property string $avatar Маленькая фотография 50x50
 * @property string $avatarBig Большая фотография 400xn
 * @property integer $genderId Пол: 0 - неопр., 1 - женский, 2 - мужской
 * @property integer $tileId ID Тайла где он живёт
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
 * @property Citizenship[] $approvedCitizenships Гражданства (подтверждённые)
 * @property Citizenship[] $requestedCitizenships Гражданства (неподтверждённые)
 * @property Party[] $parties Партии
 * @property AgencyPost[] $posts Посты
 * @property PartyPost[] $partyPosts Посты в партиях
 * @property Notification[] $notifications Уведомления
 * @property Building[] $buildings Здания которыми он управляет
 * @property BuildingTwotiled[] $buildingsTwotiled Двухтайловые здания которыми он управляет
 * @property Unit[] $units Движимые организации которыми он управляет
 * @property Massmedia[] $massmedias Массмедиа которыми он управляет
 * @property Company[] $companies Компании, директором которых является
 * @property Account[] $accounts Аккаунты
 * @property Ideology $ideology Идеология
 * @property Religion $religion Религия
 * @property Tile $tile Тайл
 * @property Modifier[] $modifiers Модификаторы
 */
class User extends TaxPayerModel implements IdentityInterface
{

    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return UtrType::USER;
    }
        
    /**
     * Является ли плательщик правительством страны
     * @param integer $stateId
     * @return boolean
     */
    public function isGoverment(int $stateId)
    {
        return false;
    }
            
    /**
     * ID страны, в которой он платит налоги
     * @return integer
     */
    public function getTaxStateId()
    {
        return (int)$this->location->getTaxStateId();
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState(int $stateId)
    {
        return (int)$this->location->isTaxedInState($stateId);
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return (int)$this->id;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController(int $userId)
    {
        return (int)$this->id === $userId;
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
            [['name', 'avatar', 'avatarBig', 'genderId', 'dateLastLogin'], 'required'],
            [['genderId'], 'integer', 'min' => 0, 'max' => 2],
            [['tileId', 'ideologyId', 'religionId', 'dateCreated', 'dateLastLogin', 'utr'], 'integer', 'min' => 0],
            [['fame', 'trust', 'success', 'fameBase', 'trustBase', 'successBase'], 'integer'],
            [['fame', 'trust', 'success', 'fameBase', 'trustBase', 'successBase'], 'default', 'value' => 0],
            [['name'], 'string', 'max' => 255],
            [['avatar', 'avatarBig'], 'string'],
            [['isInvited'], 'boolean'],
            [['isInvited'], 'default', 'value' => false],
            [['tileId'], 'exist', 'targetClass' => Tile::className(), 'targetAttribute' => ['tileId' => 'id']],
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
        return $this->hasMany(Party::className(), ['id' => 'partyId'])
            ->via('memberships')
            ->where(['is not', 'dateConfirmed', null]);
    }
        
    public function getStates()
    {
        return $this->hasMany(State::className(), ['id' => 'stateId'])
            ->via('citizenships');
    }
    
    public function getPosts()
    {
        return $this->hasMany(AgencyPost::className(), ['userId' => 'id']);
    }
    
    public function getPostsByState($id)
    {
        return $this->getPosts()->where(['stateId' => $id]);
    }
    
    public function getPartyPosts()
    {
        return $this->hasMany(PartyPost::className(), ['userId' => 'id']);
    }

    public function getCitizenships()
    {
	return $this->hasMany(Citizenship::classname(), ['userId' => 'id']);
    }
    
    public function getApprovedCitizenships()
    {
        return $this->hasMany(Citizenship::classname(), ['userId' => 'id'])->where(['>', 'dateApproved', 0]);
    }
    
    public function getRequestedCitizenships()
    {
        return $this->hasMany(Citizenship::classname(), ['userId' => 'id'])->where(['dateApproved' => null]);
    }
    
    public function isHaveCitizenship($stateId)
    {
        return !!$this->getCitizenships()->where(['stateId' => $stateId])->count();
    }
    
    public function getMemberships()
    {
	return $this->hasMany(Membership::classname(), ['userId' => 'id']);
    }
    
    public function getApprovedMemberships()
    {
        return $this->hasMany(Membership::classname(), ['userId' => 'id'])->where(['>', 'dateApproved', 0]);
    }
    
    public function getRequestedMemberships()
    {
        return $this->hasMany(Membership::classname(), ['userId' => 'id'])->where(['dateApproved' => null]);
    }
    
    public function isHaveMembership($partyId)
    {
        return !!$this->getMemberships()->where(['partyId' => $partyId])->andWhere(['>', 'dateApproved', 0])->count();
    }
    
    public function isHaveMembershipRequest($partyId)
    {
        return !!$this->getMemberships()->where(['partyId' => $partyId])->andWhere(['dateApproved' => null])->count();
    }
    
    public function getModifiers()
    {
        return $this->hasMany(Modifier::className(), ['userId' => 'id'])->where(['<', 'dateExpired', time()]);
    }
    
    public function getModifiersAll()
    {
        return $this->hasMany(Modifier::className(), ['userId' => 'id']);
    }
    
    public function getTile()
    {
        return $this->hasOne(Tile::className(), ['id' => 'tileId']);
    }
    
    /**
     * 
     * @param integer $protoId
     * @param integer $dateReceiving
     * @param integer $dateExpired
     * @param boolean $save
     * @return Modifier
     */
    public function addModifier($protoId, $dateReceiving = null, $dateExpired = null, $save = true)
    {
        $modifier = new Modifier([
            'userId' => $this->id,
            'protoId' => $protoId,
            'dateReceiving' => $dateReceiving ?? time(),
            'dateExpired' => $dateExpired
        ]);
        if ($save) {
            $modifier->save();
        }
        
        return $modifier;
    }
    
    public function updateParams()
    {
        $this->fame = $this->fameBase;
        $this->trust = $this->trustBase;
        $this->success = $this->successBase;
        
        foreach ($this->modifiers as $modifier) {
            $this->fame += $modifier->fame;
            $this->trust += $modifier->trust;
            $this->success += $modifier->success;
        }
        
        $this->save();
    }

    public function getDealingsInitiated()
    {
        if (!$this->utr) {
            $this->getUtr();
        }
	return $this->hasMany(Dealing::classname(), ['initiator' => 'utr']);
    }
    
    public function getDealingsReceived()
    {
        if (!$this->utr) {
            $this->getUtr();
        }
	return $this->hasMany(Dealing::classname(), ['receiver' => 'utr']);
    }
    
}
