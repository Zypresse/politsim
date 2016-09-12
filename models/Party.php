<?php

namespace app\models;

use Yii,
    app\components\MyModel,
    app\components\TaxPayer;

/**
 * Политическая партия
 * 
 * @property integer $id 
 * @property integer $stateId 
 * @property string $name
 * @property string $nameShort
 * @property string $flag
 * @property string $anthem
 * @property integer $ideologyId
 * @property string $text
 * @property integer $fame
 * @property integer $trust
 * @property integer $success
 * @property integer $membersCount
 * @property integer $leaderPostId
 * @property integer $joiningRules
 * @property integer $listCreationRules
 * @property integer $dateCreated
 * @property integer $dateDeleted
 * @property integer $utr
 * 
 * @property State $state
 * @property PartyPost $leaderPost
 * @property PartyPost[] $posts
 * @property PartyList[] $lists
 * @property Ideology $ideology
 * @property User[] $members
 * 
 */
class Party extends MyModel implements TaxPayer
{
    
    /**
     * Частная (заявки запрещены)
     */
    const JOINING_RULES_PRIVATE = 0;
    
    /**
     * Закрытая (заявки утверждаются)
     */
    const JOINING_RULES_CLOSED = 1;
    
    /**
     * Открытая (заявки автоподтверждаются)
     */
    const JOINING_RULES_OPEN = 2;
    
    /**
     * Избирательный список составляется лидером
     */
    const LIST_CREATION_RULES_LEADER = 1;
    
    /**
     * Избирательный список составляется по праймериз
     */
    const LIST_CREATION_RULES_PRIMARIES = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameShort', 'stateId', 'ideologyId', 'joiningRules', 'listCreationRules'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['nameShort'], 'string', 'max' => 6],
            [['flag', 'anthem', 'text'], 'string'],
            [['stateId', 'ideologyId', 'leaderPostId', 'joiningRules', 'listCreationRules', 'membersCount', 'dateCreated', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
            [['fame', 'trust', 'success'], 'integer'],
            [['anthem'], 'validateAnthem'],
            [['flag'], 'validateFlag'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => Yii::t('app', 'Party name'),
            'nameShort' => Yii::t('app', 'Party short name'),
            'ideologyId' => Yii::t('app', 'Ideology'),
            'joiningRules' => Yii::t('app', 'Joining'),
            'listCreationRules' => Yii::t('app', 'Election list creation'),
        ];
    }


    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return Utr::TYPE_PARTY;
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
        return $this->stateId;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState($stateId)
    {
        return $this->stateId === $stateId;
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return $this->leaderPost->userId;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController($userId)
    {
        return $this->leaderPost->userId == $userId;
    }    
    
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->dateCreated = time();
        }
        return parent::beforeSave($insert);
    }         
         
    public function getState()
    {
        return $this->hasOne(State::classname(), ['id' => 'stateId']);
    }
         
    public function getLeaderPost()
    {
        return $this->hasOne(PartyPost::classname(), ['id' => 'leaderPostId']);
    }
         
    public function getPosts()
    {
        return $this->hasMany(PartyPost::classname(), ['partyId' => 'id']);
    }
         
    public function getMembers()
    {
        return $this->hasMany(User::classname(), ['partyId' => 'id']);
    }
         
    public function getLists()
    {
        return $this->hasMany(PartyList::classname(), ['partyId' => 'id']);
    }
    
    private $_ideology = null;
    public function getIdeology()
    {
        if (is_null($this->_ideology)) {
            $this->_ideology = Ideology::findOne($this->ideologyId);
        }
        return $this->_ideology;
    }
    
}
