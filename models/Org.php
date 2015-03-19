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
 * @property integer $can_vote_for_bills
 * @property integer $can_create_bills
 * @property integer $leader_can_make_dicktator_bills
 * @property integer $leader_can_vote_for_bills
 * @property integer $leader_can_create_bills
 * @property integer $leader_can_veto_bills

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
            [['state_id', 'leader_post', 'leader_can_create_posts', 'next_elect', 'elect_period', 'other_org_id', 'vote_party_id', 'elect_with_org', 'elect_leader_with_org', 'group_id', 'can_vote_for_bills', 'can_create_bills','leader_can_make_dicktator_bills','leader_can_vote_for_bills','leader_can_create_bills','leader_can_veto_bills'], 'integer'],
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
            'can_vote_for_bills' => 'Организация может голосовать за законопроекты',
            'can_create_bills' => 'Организация может создавать законопроекты'
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
    
    const DEST_NATION_INDIVIDUAL_VOTE = 'nation_individual_vote';
    const DEST_NATION_PARTY_VOTE = 'nation_party_vote';
    const DEST_ORG_VOTE = 'org_vote';
    const DEST_UNLIMITED = 'unlimited';
    const DEST_BY_LEADER = 'dest_by_leader';
    


    const EXECUTIVE_JUNTA = 12340;
    const EXECUTIVE_PRESIDENT = 12341;
    const EXECUTIVE_PRIMEMINISTER = 12342;
    const LEGISLATURE_PARLIAMENT10 = 12345;
    
    /**
     * Генерация организации по одному из типов выше
     * @param int $state_id
     * @param int $type
     */
    public static function generateOrg($state_id,$type)
    {
        $state = State::findByPk($state_id);
        $org = new Org();
        $org->state_id = $state->id;
        switch ($type) {
            case static::EXECUTIVE_JUNTA:
                $org->name = "Правительство ".$state->short_name;
                $org->dest = static::DEST_BY_LEADER;
                $org->leader_dest = static::DEST_UNLIMITED;
                $org->leader_can_veto_bills = 1;
                $org->leader_can_make_dicktator_bills = 1;
                $org->leader_can_create_posts = 1;
                $org->save();
                    $post = new Post();
                    $post->name = 'Президент';
                    $post->org_id = $org->id;
                    $post->save();
                    $org->leader_post = $post->id;
                    
                    $post = new Post();
                    $post->name = 'Министр обороны';
                    $post->ministry_name = 'Министерство обороны';
                    $post->org_id = $org->id;
                    $post->save();
                    
                    $post = new Post();
                    $post->name = 'Министр экономики';
                    $post->ministry_name = 'Министерство экономики';
                    $post->org_id = $org->id;
                    $post->save();
                    
                    $post = new Post();
                    $post->name = 'Министр промышленности';
                    $post->ministry_name = 'Министерство промышленности';
                    $post->org_id = $org->id;
                    $post->save();
                    
                break;
            case static::EXECUTIVE_PRESIDENT:
                $org->name = "Правительство ".$state->short_name;
                $org->dest = static::DEST_BY_LEADER;
                $org->leader_dest = static::DEST_NATION_INDIVIDUAL_VOTE;
                $org->leader_can_veto_bills = 1;
                $org->leader_can_create_posts = 1;
                $org->elect_period = 14;
                $org->save();
                    $post = new Post();
                    $post->name = 'Президент';
                    $post->org_id = $org->id;
                    $post->save();
                    $org->leader_post = $post->id;
                    
                    $post = new Post();
                    $post->name = 'Министр обороны';
                    $post->ministry_name = 'Министерство обороны';
                    $post->org_id = $org->id;
                    $post->save();
                    
                    $post = new Post();
                    $post->name = 'Министр экономики';
                    $post->ministry_name = 'Министерство экономики';
                    $post->org_id = $org->id;
                    $post->save();
                    
                    $post = new Post();
                    $post->name = 'Министр промышленности';
                    $post->ministry_name = 'Министерство промышленности';
                    $post->org_id = $org->id;
                    $post->save();
                break;
            case static::EXECUTIVE_PRIMEMINISTER:
                $org->name = "Правительство ".$state->short_name;
                $org->dest = static::DEST_BY_LEADER;
                $org->leader_dest = static::DEST_NATION_PARTY_VOTE;
                $org->leader_can_create_posts = 1;
                $org->elect_period = 14;
                $org->save();
                    $post = new Post();
                    $post->name = 'Президент';
                    $post->org_id = $org->id;
                    $post->save();
                    $org->leader_post = $post->id;
                    
                    $post = new Post();
                    $post->name = 'Министр обороны';
                    $post->ministry_name = 'Министерство обороны';
                    $post->org_id = $org->id;
                    $post->save();
                    
                    $post = new Post();
                    $post->name = 'Министр экономики';
                    $post->ministry_name = 'Министерство экономики';
                    $post->org_id = $org->id;
                    $post->save();
                    
                    $post = new Post();
                    $post->name = 'Министр промышленности';
                    $post->ministry_name = 'Министерство промышленности';
                    $post->org_id = $org->id;
                    $post->save();
                break;
            case static::LEGISLATURE_PARLIAMENT10:
                $org->name = "Парламент ".$state->short_name;
                $org->dest = static::DEST_NATION_PARTY_VOTE;
                $org->leader_dest = static::DEST_ORG_VOTE;
                $org->leader_can_vote_for_bills = 1;
                $org->leader_can_create_bills = 1;
                $org->can_vote_for_bills = 1;
                $org->can_create_bills = 1;
                $org->elect_period = 14;
                $org->next_elect = time() + 2*24*60*60;
                $org->save();
                    $post = new Post();
                    $post->name = 'Спикер';
                    $post->org_id = $org->id;
                    $post->save();
                    $org->leader_post = $post->id;
                    
                    for ($i=0;$i<10;$i++) {
                        $post = new Post();
                        $post->name = 'Парламентарий';
                        $post->org_id = $org->id;
                        $post->save();
                    }
                break;
        }
        $org->save();
        return $org;
    }
}
