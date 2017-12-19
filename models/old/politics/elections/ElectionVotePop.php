<?php

namespace app\models\politics\elections;

use Yii,
    app\models\base\MyActiveRecord;

/**
 * Голос нпц на выборах
 *
 * @property integer $id
 * @property integer $electionId
 * @property integer $count
 * @property integer $tileId
 * @property integer $classId
 * @property integer $nationId
 * @property integer $ideologyId
 * @property integer $religionId
 * @property integer $gender
 * @property integer $age
 * @property integer $districtId
 * @property integer $variant
 * @property integer $dateCreated
 * 
 * @property Election $election
 * @property ElectoralDistrict $district
 * 
 */
class ElectionVotePop extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'electionsVotesPops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['electionId', 'count', 'tileId', 'classId', 'nationId', 'ideologyId', 'religionId', 'gender', 'age', 'districtId', 'variant'], 'required'],
            [['electionId', 'count', 'tileId', 'classId', 'nationId', 'ideologyId', 'religionId', 'gender', 'age', 'districtId', 'variant', 'dateCreated'], 'integer', 'min' => 0],
            [['electionId'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['electionId' => 'id']],
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
        
    public function getDistrict()
    {
        return $this->hasOne(ElectoralDistrict::className(), ['id' => 'districtId']);
    }
        
}
