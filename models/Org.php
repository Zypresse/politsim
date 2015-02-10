<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "orgs".
 *
 * @property integer $id
 * @property integer $state_id
 * @property string $name
 * @property integer $leader_post
 * @property string $leader_dest
 * @property string $dest
 * @property integer $leader_can_create_posts
 * @property integer $next_elect
 * @property integer $elect_period
 * @property integer $other_org_id
 * @property integer $vote_party_id
 * @property integer $elect_with_org
 * @property integer $elect_leader_with_org
 * @property integer $group_id
 */
class Org extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orgs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'name', 'leader_post', 'leader_dest', 'dest'], 'required'],
            [['state_id', 'leader_post', 'leader_can_create_posts', 'next_elect', 'elect_period', 'other_org_id', 'vote_party_id', 'elect_with_org', 'elect_leader_with_org', 'group_id'], 'integer'],
            [['name'], 'string', 'max' => 300],
            [['leader_dest', 'dest'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state_id' => 'State ID',
            'name' => 'Name',
            'leader_post' => 'Leader Post',
            'leader_dest' => 'Leader Dest',
            'dest' => 'Dest',
            'leader_can_create_posts' => 'Leader Can Create Posts',
            'next_elect' => 'Next Elect',
            'elect_period' => 'в днях',
            'other_org_id' => 'Other Org ID',
            'vote_party_id' => 'Vote Party ID',
            'elect_with_org' => 'Выборы не прямые, а вместе с выборами в другой организации',
            'elect_leader_with_org' => 'Выборы лидера не прямые а вместе с выборами в другой организации',
            'group_id' => 'Group ID',
        ];
    }

    private $publicAttributes = [
        'id',
        'state_id',
        'name',
        'leader_post',
        'leader_dest',
        'dest',
        'leader_can_create_posts',
        'next_elect',
        'elect_period',
        'other_org_id',
        'vote_party_id',
        'elect_with_org',
        'elect_leader_with_org'
    ];
}
