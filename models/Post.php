<?php

namespace app\models;

use app\components\NalogPayer,
    app\models\Unnp;

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
 * @property \app\models\Org $org Организация
 * @property \app\models\Party $partyReserve Партия, которой принадлежит этот пост
 * @property \app\models\User $user Игрок, занимающий этот пост
 * @property \app\models\Stock[] $stocks Акции, принадлежащие этому посту
 */
class Post extends NalogPayer
{

    protected function getUnnpType()
    {
        return Unnp::TYPE_POST;
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
            [['org_id', 'can_delete', 'party_reserve'], 'integer'],
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
        return $this->hasOne('app\models\Org', array('id' => 'org_id'));
    }

    public function getUser()
    {
        return $this->hasOne('app\models\User', array('post_id' => 'id'));
    }

    public function getPartyReserve()
    {
        return $this->hasOne('app\models\Party', array('id' => 'party_reserve'));
    }

    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('post_id' => 'id'));
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
     * @param \app\models\Org $org Организация
     * @param integer $type Тип (см. константы TYPE_*)
     * @return \app\models\Post
     */

    public static function generate(\app\models\Org $org, $type)
    {
        $post         = new Post();
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

}
