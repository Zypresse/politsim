<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\models\User;

/**
 * This is the model class for table "parties".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $image
 * @property integer $state_id
 * @property integer $leader
 * @property integer $ideology
 * @property integer $group_id
 * @property integer $star
 * @property integer $heart
 * @property integer $chart_pie
 */
class Party extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'state_id', 'leader', 'ideology'], 'required'],
            [['state_id', 'leader', 'ideology', 'group_id', 'star', 'heart', 'chart_pie'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['short_name'], 'string', 'max' => 30],
            [['image'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'short_name' => 'Short Name',
            'image' => 'Image',
            'state_id' => 'State ID',
            'leader' => 'Leader',
            'ideology' => 'Ideology',
            'group_id' => 'Group ID',
            'star' => 'Star',
            'heart' => 'Heart',
            'chart_pie' => 'Chart Pie',
        ];
    }

    public function getMembers()
    {
        return $this->hasMany('app\models\User', array('party_id' => 'id'))->orderBy('`star` + `heart`/10 + `chart_pie`/100 DESC');
    }
    public function getLeaderInfo()
    {
        return $this->hasOne('app\models\User', array('id' => 'leader'));
    }
    public function getRequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('party_id' => 'id'))->where(['leader'=>0]);
    }
    public function getLrequests()
    {
        return $this->hasMany('app\models\ElectRequest', array('party_id' => 'id'))->where(['leader'=>1]);
    }
    public function getState()
    {
        return $this->hasOne('app\models\State', array('id' => 'state_id'));
    }
    public function getIdeologyInfo()
    {
        return $this->hasOne('app\models\Ideology', array('id' => 'ideology'));
    }

    public function getMembersCount()
    {
        return User::find()->where(['party_id'=>$this->id])->count();
    }

    public function afterDelete()
    {
        foreach ($this->requests as $request) 
            $request->delete();
        foreach ($this->lrequests as $request) 
            $request->delete();
        
    }
}
