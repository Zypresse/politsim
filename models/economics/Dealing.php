<?php

namespace app\models\economics;

use app\models\base\MyActiveRecord;

/**
 * Сделка
 *
 * @property integer $id
 * @property integer $type
 * @property integer $initiatorId
 * @property integer $receiverId
 * @property integer $dateCreated
 * @property integer $dateApproved
 * 
 * @property TaxPayer $initiator
 * @property TaxPayer $receiver
 * @property DealingItem[] $items
 * 
 */
class Dealing extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'initiatorId', 'receiverId'], 'required'],
            [['type', 'initiatorId', 'receiverId', 'dateCreated', 'dateApproved'], 'integer', 'min' => 0],
            [['initiatorId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['initiatorId' => 'id']],
            [['receiverId'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['receiverId' => 'id']],
        ];
    }
    
    public function getInitiator()
    {
        return $this->hasOne(Utr::className(), ['id' => 'initiator'])->one()->object();
    }
    
    public function getReceiver()
    {
        return $this->hasOne(Utr::className(), ['id' => 'receiver'])->one()->object();
    }
    
    public function getItems()
    {
        return $this->hasMany(DealingItem::classname(), ['dealingId' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->dateCreated = time();
        }
        
        return parent::beforeSave($insert);
    }
}
