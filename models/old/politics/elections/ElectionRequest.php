<?php

namespace app\models\politics\elections;

use app\models\base\MyActiveRecord;

/**
 * Заявка на выборы
 *
 * @property integer $electionId
 * @property integer $type тип заявки const из ElectionRequestType
 * @property integer $objectId id заявителя / списка / проч.
 * @property integer $variant порядковый номер заявки, выставляется автоматически. можно сделать потом редактирование типа случайного перемешивания
 * @property integer $dateCreated
 * 
 * @property Election $election
 * @property \app\models\User|\app\models\politics\PartyList $object
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
    
    public function getObject()
    {
        return $this->hasOne(ElectionRequestType::getClassByType($this->type), ['id' => 'objectId']);
    }
    
    public static function primaryKey()
    {
        return ['electionId', 'variant'];
    }
    
}
