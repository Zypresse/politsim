<?php

namespace app\models\politics\elections;

use app\models\base\MyActiveRecord;

/**
 * Заявка на выборы
 *
 * @property integer $electionId
 * @property integer $type
 * @property integer $objectId
 * @property integer $variant
 * @property integer $dateCreated
 * 
 * @property Election $election
 * 
 */
class ElectionRequest extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'electionsRequests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['electionId', 'type', 'objectId', 'variant'], 'required'],
            [['electionId', 'type', 'objectId', 'variant', 'dateCreated'], 'integer', 'min' => 0],
            [['electionId'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['electionId' => 'id']],
            [['electionId', 'type', 'objectId'], 'unique', 'targetAttribute' => ['electionId', 'type', 'objectId']],
            [['electionId', 'variant'], 'unique', 'targetAttribute' => ['electionId', 'variant']],
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
    
    public static function primaryKey()
    {
        return ['electionId', 'variant'];
    }
    
}
