<?php

namespace app\models;

use Yii;
use app\components\MyModel;

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
}
