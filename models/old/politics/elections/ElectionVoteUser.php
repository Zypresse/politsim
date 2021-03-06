<?php

namespace app\models\politics\elections;

use app\models\User,
    app\models\base\MyActiveRecord;

/**
 * Голос юзера на выборах
 *
 * @property integer $electionId
 * @property integer $userId
 * @property integer $districtId
 * @property integer $variant
 * @property integer $dateCreated
 * 
 * @property Election $election
 * @property User $user
 * @property ElectoralDistrict $district
 * 
 */
class ElectionVoteUser extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'electionsVotesUsers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['electionId', 'userId', 'variant'], 'required'],
            [['electionId', 'userId', 'districtId', 'variant', 'dateCreated'], 'integer', 'min' => 0],
            [['electionId'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['electionId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
            [['districtId'], 'exist', 'skipOnError' => true, 'targetClass' => ElectoralDistrict::className(), 'targetAttribute' => ['districtId' => 'id']],
        ];
    }
    
    public function beforeSave($insert)
    {
        
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getElection()
    {
        return $this->hasOne(Election::className(), ['id' => 'electionId']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    public function getDistrict()
    {
        return $this->hasOne(ElectoralDistrict::className(), ['id' => 'districtId']);
    }
    
    public static function primaryKey()
    {
        return ['electionId', 'userId'];
    }
    
}
