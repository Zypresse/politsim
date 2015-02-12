<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\models\Post;

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
            [['state_id', 'name', 'leader_dest', 'dest'], 'required'],
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

    public function setPublicAttributes() 
    {
        return [
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

    public function isElected()
    {
        return in_array($this->dest, ['nation_party_vote','nation_individual_vote']);
    }
    public function isLeaderElected()
    {
        return in_array($this->leader_dest, ['nation_party_vote','nation_individual_vote']);
    }
    public function isLegislature()
    {
        return ($this->id === $this->state->legislature);
    }
    public function isExecutive()
    {
        return ($this->id === $this->state->executive);
    }
    public function isGoingElects()
    {
        return ($this->next_elect - time() < 60*60*24);
    }

    public function getUsersCount()
    {
        return Post::find()->join('LEFT JOIN','users','users.post_id = posts.id')->where('posts.org_id = '.$this->id.' AND users.id IS NOT NULL')->count();
    }
    public function getPostsCount()
    {
        return sizeof($this->posts);
    }


    public function getLeader()
    {
        return $this->hasOne('app\models\Post', array('id' => 'leader_post'));
    }
    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    public function getPosts()
    {
        return $this->hasMany('app\models\Post', array('org_id' => 'id'));
    }
    public function getRequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('org_id' => 'id'))->where(['leader'=>0]);
    }
    public function getLrequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('org_id' => 'id'))->where(['leader'=>1]);
    }
}
