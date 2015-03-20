<?php

namespace app\models;

use app\components\MyModel;

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
class Post extends MyModel
{
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
            'id' => 'ID',
            'org_id' => 'Org ID',
            'name' => 'Name',
            'can_delete' => 'Можно ли удалять этот пост',
            'party_reserve' => 'ID партии, которой зарезервирован пост',
            'balance' => 'Balance', 
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
        return ($this->user->isOrgLeader() && ($this->org->leader_can_make_dicktator_bills || $this->org->leader_can_create_bills))
                || ($this->org->can_create_bills);
    }
    
    /**
     * Может ли голосовать по законопроектам
     * @return boolean
     */
    public function canVoteForBills()
    {
        return (($this->user->isOrgLeader() && $this->org->leader_can_vote_for_bills) || $this->org->can_vote_for_bills);
    }
}
