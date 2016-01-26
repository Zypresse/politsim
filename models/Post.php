<?php

namespace app\models;

use app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\Utr,
    app\models\Org,
    app\models\Party,
    app\models\User,
    app\models\Stock;

/**
 * Должность в правительстве. Таблица "posts".
 *
 * @property integer $id
 * @property integer $org_id ID организации
 * @property string $name Название поста
 * @property integer $can_delete Может ли этот пост быть удалён
 * @property integer $party_reserve ID партии, которой принадлежит этот пост (для назначаемых по голосованию за партии)
 * @property double $balance Бюджет поста
 * @property string $ministry_name Название министерства, министром которого является пост
 * 
 * @property Org $org Организация
 * @property Party $partyReserve Партия, которой принадлежит этот пост
 * @property User $user Игрок, занимающий этот пост
 */
class Post extends MyModel implements TaxPayer
{

    public function getUnnpType()
    {
        return Utr::TYPE_POST;
    }

    public function getStocks()
    {
        return $this->hasMany(Stock::className(), array('unnp' => 'unnp'));
    }
    
    public function getUnnp() {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->utr = ($u) ? $u->id : 0;
            $this->save();
        } 
        return $this->utr;
    }

    public function isGoverment($stateId)
    {
        return $this->org->state_id === $stateId;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id', 'name'], 'required'],
            [['balance'], 'number'],
            [['org_id', 'can_delete', 'party_reserve', 'utr'], 'integer'],
            [['name'], 'string', 'max' => 300],
            [['ministry_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'org_id'        => 'Org ID',
            'name'          => 'Name',
            'can_delete'    => 'Можно ли удалять этот пост',
            'party_reserve' => 'ID партии, которой зарезервирован пост',
            'balance'       => 'Balance',
            'ministry_name' => 'Ministry Name',
        ];
    }

    public function getOrg()
    {
        return $this->hasOne(Org::className(), array('id' => 'org_id'));
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), array('post_id' => 'id'));
    }

    public function getPartyReserve()
    {
        return $this->hasOne(Party::className(), array('id' => 'party_reserve'));
    }

    /**
     * Может ли создавать законопроекты
     * @return boolean
     */
    public function canCreateBills()
    {
        return ($this->user->isOrgLeader() && ($this->org->leader_can_make_dicktator_bills || $this->org->leader_can_create_bills)) || ($this->org->can_create_bills);
    }

    /**
     * Может ли голосовать по законопроектам
     * @return boolean
     */
    public function canVoteForBills()
    {
        return (($this->user->isOrgLeader() && $this->org->leader_can_vote_for_bills) || $this->org->can_vote_for_bills);
    }
    
    /**
     * Может ли накладывать вето на законопроекты
     * @return boolean
     */
    public function canVetoBills()
    {
        return ($this->user->isOrgLeader() && $this->org->leader_can_veto_bills);
    }

    /*
     * Типы постов
     */

    const TYPE_PRESIDENT         = 543; // Президент
    const TYPE_MINISTER_PRIME    = 5430; // Премьер-министр
    const TYPE_MINISTER_DEFENCE  = 544; // Министр обороны
    const TYPE_MINISTER_ECONOMY  = 545; // Министр экономики
    const TYPE_MINISTER_INDUSTRY = 546; // Министр промышленности
    const TYPE_SPEAKER           = 547; // Спикер
    const TYPE_PARLAMENTARIAN    = 548; // Парламентарий

    /**
     * Генерирует пост по заданому шаблону
     * @param Org $org Организация
     * @param integer $type Тип (см. константы TYPE_*)
     * @return Post
     */

    public static function generate(Org $org, $type)
    {
        $post         = new self();
        $post->org_id = $org->id;
        switch ($type) {
            case static::TYPE_PRESIDENT:
                $post->name          = 'Президент';
                break;
            case static::TYPE_MINISTER_PRIME:
                $post->name          = 'Премьер-министр';
                break;
            case static::TYPE_MINISTER_DEFENCE:
                $post->name          = 'Министр обороны';
                $post->ministry_name = 'Министерство обороны';
                break;
            case static::TYPE_MINISTER_ECONOMY:
                $post->name          = 'Министр экономики';
                $post->ministry_name = 'Министерство экономики';
                break;
            case static::TYPE_MINISTER_INDUSTRY:
                $post->name          = 'Министр промышленности';
                $post->ministry_name = 'Министерство промышленности';
                break;
            case static::TYPE_SPEAKER:
                $post->name          = 'Спикер';
                break;
            case static::TYPE_PARLAMENTARIAN:
                $post->name          = 'Парламентарий';
                break;
        }
        $post->save();
        return $post;
    }

    public function changeBalance($delta)
    {
        $this->balance += $delta;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getHtmlName()
    {
        return $this->name." организации «".$this->org->getHtmlName()."»";
    }

    public function getTaxStateId()
    {
        return $this->org->state_id;
    }

    public function isTaxedInState($stateId)
    {
        return $this->org->state_id === $stateId;
    }

    public function getUserControllerId()
    {
        return $this->user->id;
    }

    public function isUserController($userId)
    {
        return $this->user->id === $userId;
    }

}
