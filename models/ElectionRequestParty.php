<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Партийная заявка на выборы
 *
 * @property integer $electionId
 * @property integer $partyId
 * @property integer $partyListId
 * @property integer $variant
 * @property integer $dateCreated
 * 
 * @property Election $election
 * @property Party $party
 * @property PartyList $partyList
 * 
 */
class ElectionRequestParty extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elections-requests-party';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['electionId', 'partyId', 'partyListId', 'variant'], 'required'],
            [['electionId', 'partyId', 'partyListId', 'variant', 'dateCreated'], 'integer', 'min' => 0],
            [['electionId'], 'exist', 'skipOnError' => true, 'targetClass' => Election::className(), 'targetAttribute' => ['electionId' => 'id']],
            [['partyId'], 'exist', 'skipOnError' => true, 'targetClass' => Party::className(), 'targetAttribute' => ['partyId' => 'id']],
            [['partyListId'], 'exist', 'skipOnError' => true, 'targetClass' => PartyList::className(), 'targetAttribute' => ['partyListId' => 'id']],
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
    
    public function getParty()
    {
        return $this->hasOne(Party::className(), ['id' => 'partyId']);
    }
    
    public function getPartyList()
    {
        return $this->hasOne(PartyList::className(), ['id' => 'partyListId']);
    }
    
    public function primaryKey()
    {
        return ['electionId', 'variant'];
    }
    
}
