<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property integer $org_id
 * @property string $name
 * @property string $type
 * @property integer $can_delete
 * @property integer $party_reserve
 * @property double $balance 
 * @property string $ministry_name 
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
            [['org_id', 'name', 'type'], 'required'],
            [['balance'], 'number'], 
            [['org_id', 'can_delete', 'party_reserve'], 'integer'],
            [['name'], 'string', 'max' => 300],
            [['type'], 'string', 'max' => 100],
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
            'type' => 'Type',
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
    
    public function canCreateBills()
    {
        return ($this->user->isOrgLeader() && ($this->org->leader_can_make_dicktator_bills || $this->org->leader_can_create_bills))
                || ($this->org->can_create_bills);
    }
    
    public function canVoteForBills()
    {
        return (($this->user->isOrgLeader() && $this->org->leader_can_vote_for_bills) || $this->org->can_vote_for_bills);
    }
}
