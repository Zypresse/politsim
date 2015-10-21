<?php

namespace app\models;

use app\components\NalogPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\Post,
    app\models\Unnp;

/**
 * Организация. Таблица "orgs".
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
 * @property integer $can_vote_for_bills
 * @property integer $can_create_bills
 * @property integer $can_drop_stateleader
 * @property integer $leader_can_make_dicktator_bills
 * @property integer $leader_can_vote_for_bills
 * @property integer $leader_can_create_bills
 * @property integer $leader_can_veto_bills
 * 
 * @property \app\models\Post $leader Лидер организации
 * @property \app\models\State $state Государство
 * @property \app\models\Post[] $posts Посты
 * @property \app\models\ElectRequest[] $requests Заявки на выборы членов
 * @property \app\models\ElectRequest[] $lrequests Заявки на выборы лидера
 * @property \app\models\ElectOrgLeaderRequest[] $speakerRequests Заявки на выборы лидера по голосованию организации
 */
class Org extends MyModel implements NalogPayer {

    protected function getUnnpType()
    {
        return Unnp::TYPE_ORG;
    }

    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('unnp' => 'unnp'));
    }
    
    private $_unnp;
    public function getUnnp() {
        if (is_null($this->_unnp)) {
            $u = Unnp::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->_unnp = ($u) ? $u->id : 0;
        } 
        return $this->_unnp;
    }
    
    public function isGoverment($stateId)
    {
        return $this->state_id === $stateId;
    }

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
            [['state_id', 'leader_post', 'leader_can_create_posts', 'next_elect', 'elect_period', 'other_org_id', 'vote_party_id', 'elect_with_org', 'elect_leader_with_org', 'can_vote_for_bills', 'can_create_bills', 'leader_can_make_dicktator_bills', 'leader_can_vote_for_bills', 'leader_can_create_bills', 'leader_can_veto_bills'], 'integer'],
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
            'can_vote_for_bills' => 'Организация может голосовать за законопроекты',
            'can_create_bills' => 'Организация может создавать законопроекты'
        ];
    }

    /**
     * Выбираются ли члены организации народным голосованием
     * @return boolean
     */
    public function isElected()
    {
        return in_array($this->dest, [static::DEST_NATION_PARTY_VOTE, static::DEST_NATION_INDIVIDUAL_VOTE]);
    }

    /**
     * Выбирается ли лидер народным голосованием
     * @return boolean
     */
    public function isLeaderElected()
    {
        return in_array($this->leader_dest, [static::DEST_NATION_PARTY_VOTE, static::DEST_NATION_INDIVIDUAL_VOTE]);
    }

    /**
     * Это законодательная власть?
     * @return boolean
     */
    public function isLegislature()
    {
        return $this->state && ($this->id === $this->state->legislature);
    }

    /**
     * Это исполнительная власть?
     * @return boolean
     */
    public function isExecutive()
    {
        return $this->state && ($this->id === $this->state->executive);
    }

    /**
     * Идут ли прямо сейчас выборы
     * @return boolean
     */
    public function isGoingElects()
    {
        return $this->next_elect && ($this->next_elect - time() < 60 * 60 * 24);
    }

    /**
     * Возвращает число заполненных постов в организации
     * @return integer
     */
    public function getUsersCount()
    {
        return intval(Post::find()->join('LEFT JOIN', 'users', 'users.post_id = posts.id')->where('posts.org_id = ' . $this->id . ' AND users.id IS NOT NULL')->count());
    }

    /**
     * Возвращает общее число постов в организации
     * @return integer
     */
    public function getPostsCount()
    {
        return intval($this->hasMany('app\models\Post', array('org_id' => 'id'))->count());
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
        return $this->hasMany('app\models\ElectRequest', array('org_id' => 'id'))->where(['leader' => 0]);
    }

    public function getLrequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('org_id' => 'id'))->where(['leader' => 1]);
    }

    public function getSpeakerRequests()
    {
        return $this->hasMany('app\models\ElectOrgLeaderRequest', array('org_id' => 'id'));
    }

    public function isAllreadySpeakerVoted($post_id)
    {
        $ret = false;
        foreach ($this->speakerRequests as $request) {
            foreach ($request->votes as $vote) {
                if ($vote->post_id === $post_id) {
                    $ret = true;
                    break;
                }
            }
        }

        return $ret;
    }

    /*
     * Типы назначения членов и/или лидера организации
     */

    const DEST_NATION_INDIVIDUAL_VOTE = 'nation_individual_vote';
    const DEST_NATION_PARTY_VOTE = 'nation_party_vote';
    const DEST_ORG_VOTE = 'org_vote';
    const DEST_UNLIMITED = 'unlimited';
    const DEST_BY_LEADER = 'dest_by_leader';


    /*
     * Стандартные типы организаций
     * Используются для только генераций новых
     */
    const EXECUTIVE_JUNTA = 12340; // Исполнительная власть хунты
    const EXECUTIVE_PRESIDENT = 12341; // Исполнительная власть президентской республики
    const EXECUTIVE_PRIMEMINISTER = 12342; // Исполнительная власть парламентской республики
    const LEGISLATURE_PARLIAMENT10 = 12345; // Стандартный парламент на 10 парламентариев и спикера

    /**
     * Генерация организации по одному из типов выше
     * @param \app\models\State $state
     * @param int $type
     * @return \app\models\Org
     */

    public static function generate(\app\models\State $state, $type)
    {
        $org = new Org();
        $org->state_id = $state->id;
        switch ($type) {
            case static::EXECUTIVE_JUNTA:
                $org->name = "Правительство " . $state->short_name;
                $org->dest = static::DEST_BY_LEADER;
                $org->leader_dest = static::DEST_UNLIMITED;
                $org->leader_can_veto_bills = 1;
                $org->leader_can_make_dicktator_bills = 1;
                $org->leader_can_create_posts = 1;
                $org->save();
                $post = Post::generate($org, Post::TYPE_PRESIDENT);
                $org->leader_post = $post->id;

                Post::generate($org, Post::TYPE_MINISTER_DEFENCE);
                Post::generate($org, Post::TYPE_MINISTER_ECONOMY);
                Post::generate($org, Post::TYPE_MINISTER_INDUSTRY);

                break;
            case static::EXECUTIVE_PRESIDENT:
                $org->name = "Правительство " . $state->short_name;
                $org->dest = static::DEST_BY_LEADER;
                $org->leader_dest = static::DEST_NATION_INDIVIDUAL_VOTE;
                $org->leader_can_veto_bills = 1;
                $org->leader_can_vote_for_bills = 1;
                $org->leader_can_create_bills = 1;
                $org->leader_can_create_posts = 1;
                $org->elect_period = 14;
                $org->next_elect = time() + 2 * 24 * 60 * 60;
                $org->save();
                $post = Post::generate($org, Post::TYPE_PRESIDENT);
                $org->leader_post = $post->id;

                Post::generate($org, Post::TYPE_MINISTER_DEFENCE);
                Post::generate($org, Post::TYPE_MINISTER_ECONOMY);
                Post::generate($org, Post::TYPE_MINISTER_INDUSTRY);
                break;
            case static::EXECUTIVE_PRIMEMINISTER:
                $org->name = "Правительство " . $state->short_name;
                $org->dest = static::DEST_BY_LEADER;
                $org->leader_dest = static::DEST_NATION_PARTY_VOTE;
                $org->leader_can_create_posts = 1;
                $org->elect_period = 14;
                $org->next_elect = time() + 2 * 24 * 60 * 60;
                $org->save();
                $post = Post::generate($org, Post::TYPE_MINISTER_PRIME);
                $org->leader_post = $post->id;

                Post::generate($org, Post::TYPE_MINISTER_DEFENCE);
                Post::generate($org, Post::TYPE_MINISTER_ECONOMY);
                Post::generate($org, Post::TYPE_MINISTER_INDUSTRY);
                break;
            case static::LEGISLATURE_PARLIAMENT10:
                $org->name = "Парламент " . $state->short_name;
                $org->dest = static::DEST_NATION_PARTY_VOTE;
                $org->leader_dest = static::DEST_ORG_VOTE;
                $org->leader_can_vote_for_bills = 1;
                $org->leader_can_create_bills = 1;
                $org->can_vote_for_bills = 1;
                $org->can_create_bills = 1;
                $org->elect_period = 14;
                $org->next_elect = time() + 2 * 24 * 60 * 60;
                $org->save();
                $post = Post::generate($org, Post::TYPE_SPEAKER);
                $org->leader_post = $post->id;

                for ($i = 0; $i < 10; $i++) {
                    Post::generate($org, Post::TYPE_PARLAMENTARIAN);
                }
                break;
        }
        $org->save();
        return $org;
    }

    public function afterDelete()
    {
        foreach ($this->posts as $post) {
            $post->delete();
        }
    }

    public function changeBalance($delta)
    {
        
    }

    public function getBalance()
    {
        return 0;
    }

    public function getHtmlName()
    {
        return MyHtmlHelper::a($this->name, "load_page('org-info',{'id':{$this->id}})");
    }

}
