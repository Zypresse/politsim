<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\models\Twitter;
use app\models\Dealing;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property integer $uid_vk
 * @property string $name
 * @property string $photo
 * @property string $photo_big
 * @property integer $last_vote
 * @property integer $last_tweet
 * @property integer $last_salary
 * @property integer $party_id
 * @property integer $state_id
 * @property integer $post_id
 * @property integer $region_id
 * @property double $money
 * @property integer $sex
 * @property integer $star
 * @property integer $heart
 * @property integer $chart_pie
 */
class User extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid_vk', 'last_vote', 'last_tweet', 'last_salary', 'party_id', 'state_id', 'post_id', 'region_id', 'sex', 'star', 'heart', 'chart_pie'], 'integer'],
            [['money'], 'number'],
            [['name', 'photo', 'photo_big'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid_vk' => 'Uid Vk',
            'name' => 'Name',
            'photo' => 'Photo',
            'photo_big' => 'Photo Big',
            'last_vote' => 'Last Vote',
            'last_tweet' => 'Last Tweet',
            'last_salary' => 'Last Salary',
            'party_id' => 'Party ID',
            'state_id' => 'State ID',
            'post_id' => 'Post ID',
            'region_id' => 'Region ID',
            'money' => 'Money',
            'sex' => 'Sex',
            'star' => 'Star',
            'heart' => 'Heart',
            'chart_pie' => 'Chart Pie',
        ];
    }

    public function setPublicAttributes() 
    {
        return [
            'id',
            'uid_vk',
            'name',
            'photo',
            'photo_big',
            'party_id',
            'state_id',
            'post_id',
            'region_id',
            'sex',
            'star',
            'heart',
            'chart_pie',
        ];
    }

    public function getParty()
    {
        return $this->hasOne('app\models\Party', array('id' => 'party_id'));
    }
    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    public function getPost()
    {
        return $this->hasOne('app\models\Post', array('id' => 'post_id'));
    }
    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }
    public function getMedales()
    {
        return $this->hasMany('app\models\Medale', array('uid' => 'id'));
    }
    public function getVotes()
    {
        return $this->hasMany('app\models\ElectVote', array('uid' => 'id'));
    }
    public function getStocks()
    {
        return $this->hasMany('app\models\Stock', array('user_id' => 'id'));
    }
    public function getRequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('candidat' => 'id'));
    }
    public function getNotifications()
    {
        return $this->hasMany('app\models\Notification', array('uid' => 'id'));
    }
    
    public function getMyDealingsList()
    {
        return Dealing::getMyList($this->id);
    }
    
    public function getNotAcceptedDealingsList()
    {
        return Dealing::find()->where(['to_uid'=>$this->id,'time'=>-1])->all();
    }
    
    public function getNotAcceptedDealingsCount()
    {
        return Dealing::find()->where(['to_uid'=>$this->id,'time'=>-1])->count();
    }

    public function isPartyLeader()
    {
        return (intval(@$this->party->leader) === intval($this->id));
    }

    public function isOrgLeader()
    {
        return (intval($this->post_id) && intval(@$this->post->org->leader_post) === intval($this->post_id));
    }

    public function isStateLeader()
    {
        return ($this->isOrgLeader() && $this->post->org->isExecutive());
    }
    
    public function isShareholder(Holding $holding)
    {
        foreach ($holding->stocks as $stock) {
            if ($stock->user_id === $this->id || $stock->post_id === $this->post_id) return true;
        }
        return false;
    }
    
    public function getShareholderStock(Holding $holding)
    {
        foreach ($holding->stocks as $stock) {
            if ($stock->user_id === $this->id || $stock->post_id === $this->post_id) return $stock;
        }
        return null;
    }
    
    public function isHaveControllingStake(Holding $holding)
    {
        foreach ($holding->stocks as $stock) {
            if ($stock->user_id === $this->id && $stock->getPercents()>50.0) return true;
        }
        return false;
    }

    public function getTwitterSubscribersCount()
    {
        return $this->star*100 + $this->chart_pie*10 + abs($this->heart);
    }

    public function getTweetsCount()
    {
        return Twitter::find()->where(['uid'=>$this->id])->count();
    }

    public function leaveParty()
    {
        if ($this->party) {
            if (intval($this->party->getMembersCount()) === 1) {
                $party->delete();
            }            
        }
        $this->party_id = 0;
        if ($this->post && $this->post->party_reserve) {
            $this->post_id = 0;
        }

        return $this->save();
    }
    public function leaveState()
    {
        foreach ($this->requests as $request) 
            $request->delete();

        $this->state_id = 0;
        $this->post_id = 0;
        
        return $this->leaveParty();
    }
}
