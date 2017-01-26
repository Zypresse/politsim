<?php

namespace app\models\politics;

use Yii,
    app\models\economics\UtrType,
    app\models\economics\TaxPayerModel,
    app\models\Ideology,
    app\models\User,
    yii\helpers\Html,
    bupy7\bbcode\BBCodeBehavior;

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
 * @property string $textHTML
 * @property integer $fame
 * @property integer $trust
 * @property integer $success
 * @property integer $membersCount
 * @property integer $leaderPostId
 * @property integer $joiningRules
 * @property integer $listCreationRules
 * @property integer $dateCreated
 * @property integer $dateConfirmed
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
 * @property boolean $isConfirmed
 * @property boolean $isDeleted
 * 
 */
class Party extends TaxPayerModel
{
    
    public $purified_text;
    
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
            [['flag', 'anthem', 'text', 'textHTML'], 'string'],
            [['stateId', 'ideologyId', 'leaderPostId', 'joiningRules', 'listCreationRules', 'membersCount', 'dateCreated', 'dateConfirmed', 'dateDeleted', 'utr'], 'integer', 'min' => 0],
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
            'flag' => Yii::t('app', 'Flag'),
            'anthem' => Yii::t('app', 'Anthem'),
            'text' => Yii::t('app', 'Political program'),
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => BBCodeBehavior::className(),
                'attribute' => 'text',
                'saveAttribute' => 'textHTML',
                'codeDefinitionBuilder' => [
                    ['quote', '<blockquote>{param}</blockquote>'],
                    ['code', '<code>{param}</code>'],
                    ['sup', '<sup>{param}</sup>'],
                    ['sub', '<sub>{param}</sub>'],
                    ['ul', '<ul>{param}</ul>'],
                    ['ol', '<ol>{param}</ol>'],
                    ['li', '<li>{param}</li>'],
                ],
            ],
        ];
    }

    /**
     * Возвращает константное значение типа налогоплательщика
     * @return integer
     */
    public function getUtrType()
    {
        return UtrType::PARTY;
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
        return (int)$this->stateId;
    }
    
    /**
     * Является ли налогоплательщиком государства
     * @param integer $stateId
     * @return boolean
     */
    public function isTaxedInState(int $stateId)
    {
        return (int)$this->stateId === $stateId;
    }
    
    /**
     * ID юзера, который управляет этим налогоплательщиком
     */
    public function getUserControllerId()
    {
        return (int)$this->leaderPost->userId;
    }
    
    /**
     * Может ли юзер управлять этим налогоплательщиком
     * @param integer $userId
     * @return boolean
     */
    public function isUserController(int $userId)
    {
        return (int)$this->leaderPost->userId == $userId;
    }    
    
    public function beforeSave($insert)
    {
        $this->text = Html::encode($this->text);
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
        return $this->hasMany(PartyPost::classname(), ['partyId' => 'id'])->orderBy(['id' => SORT_ASC]);
    }
    
    /**
     * 
     * @param integer $userId
     * @return PartyPost
     */
    public function getPostByUserId($userId)
    {
        return $this->getPosts()->where(['userId' => $userId])->one();
    }

    public function getMembers()
    {
        return $this->hasMany(User::classname(), ['id' => 'userId'])
                 ->via('approvedMemberships');
    }
    
    public function getMemberships()
    {
	return $this->hasMany(Membership::classname(), ['partyId' => 'id']);
    }
    
    public function getApprovedMemberships()
    {
        return $this->hasMany(Membership::classname(), ['partyId' => 'id'])->where(['>', 'dateApproved', 0]);
    }
    
    public function getRequestedMemberships()
    {
        return $this->hasMany(Membership::classname(), ['partyId' => 'id'])->where(['dateApproved' => null]);
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
    
    public function updateParams($save = true)
    {
        
        $this->fame = 0;
        $this->trust = 0;
        $this->success = 0;
        $this->membersCount = 0;
        
        foreach ($this->members as $member) {
            $this->membersCount++;
            $this->fame += $member->fame;
            $this->trust += $member->trust;
            $this->success += $member->success;
        }
        
        if ($this->membersCount == 0) {
            $this->dateDeleted = time();
        }
        
        if ($save) {
            $this->save();
        }
    }
    
    /**
     * 
     * @param \app\models\User $creator
     * @param boolean $autoConfirm
     * @return boolean
     */
    public function createNew(User $creator, bool $autoConfirm = true)
    {
        if ($autoConfirm) {
            $this->confirm(false);
        }
        
        if ($this->save()) {
        
            $membership = new Membership([
                'partyId' => $this->id,
                'userId' => $creator->id,
                'dateApproved' => time()
            ]);

            if ($membership->save()) {

                $post = new PartyPost([
                    'partyId' => $this->id,
                    'userId' => $creator->id,
                    'name' => Yii::t('app', 'Party leader'),
                    'nameShort' => Yii::t('app', 'leader'),
                    'powers' => PartyPost::POWER_CHANGE_FIELDS + PartyPost::POWER_EDIT_POSTS + PartyPost::POWER_APPROVE_REQUESTS,
                    'appointmentType' => PartyPost::APPOINTMENT_TYPE_INHERITANCE
                ]);

                if ($post->save()) {
                    
                    $this->leaderPostId = $post->id;
                    
                    if ($this->save()) {
                        Yii::$app->notificator->partyCreated($creator->id, $this, true);
                        return true;
                    }
                }

                $this->addErrors($post->getErrors());
            }

            $this->addErrors($membership->getErrors());
        }
        
        return false;
    }
    
    public function getIsConfirmed() : bool
    {
        return !is_null($this->dateConfirmed);
    }
    
    public function getIsDeleted() : bool
    {
        return !is_null($this->dateDeleted);
    }
    
    /**
     * 
     * @param boolean $save
     * @return boolean
     */
    public function confirm($save = true) : bool
    {
        $this->dateConfirmed = time();
        if ($save) {
            return $this->save();
        }
        return false;
    }
    
    public function delete() : bool
    {
        $this->dateDeleted = time();
        return $this->save();
    }
    
}
