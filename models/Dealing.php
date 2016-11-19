<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Сделка
 *
 * @property integer $id
 * @property integer $protoId
 * @property integer $initiator
 * @property integer $receiver
 * @property integer $dateCreated
 * @property integer $dateApproved
 * 
 * @property \app\components\TaxPayer $initiatorObject
 * @property \app\components\TaxPayer $receiverObject
 * @property DealingItem[] $items
 * 
 */
class Dealing extends MyModel
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
            [['protoId', 'initiator', 'receiver'], 'required'],
            [['protoId', 'initiator', 'receiver', 'dateCreated', 'dateApproved'], 'integer', 'min' => 0],
            [['receiver'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['receiver' => 'id']],
            [['initiator'], 'exist', 'skipOnError' => true, 'targetClass' => Utr::className(), 'targetAttribute' => ['initiator' => 'id']],
        ];
    }
    
    public function getInitiatorObject()
    {
        return $this->hasOne(Utr::className(), ['id' => 'initiator'])->one()->object();
    }
    
    public function getReceiverObject()
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
