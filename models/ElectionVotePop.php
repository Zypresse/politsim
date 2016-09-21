<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Голос юзера на выборах
 *
 * @property integer $electionId
 * @property integer $count
 * @property string $popData
 * @property integer $districtId
 * @property integer $variant
 * @property integer $dateCreated
 * 
 * @property Election $election
 * @property ElectoralDistrict $district
 * 
 */
class ElectionVotePop extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elections-votes-pops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['electionId', 'count', 'popData', 'districtId', 'variant'], 'required'],
            [['electionId', 'count', 'districtId', 'variant', 'dateCreated'], 'integer', 'min' => 0],
            [['popData'], 'string'],
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
    
    public function primaryKey()
    {
        return null; // ???
    }
    
}
