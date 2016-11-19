<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Вещь, передаваемая в сделке
 *
 * @property integer $dealingId
 * @property boolean $direction
 * @property integer $type
 * @property integer $protoId
 * @property integer $subProtoId
 * @property integer $quality
 * @property integer $deterioration
 * @property integer $locationId
 * @property double $count
 * 
 * @property Dealing $dealing
 * @property \app\components\TaxPayer $location
 * 
 */
class DealingItem extends MyModel
{
    
    /**
     * от иницаиатора к ресиверу
     */
    const DIRECTION_INITIATOR_TO_RECIVIER = false;
    
    /**
     * от ресивера к инициатору
     */
    const DIRECTION_RECIVIER_TO_INITIATOR = true;
    
    /**
     * деньги (id валюты)
     */
    const TYPE_MONEY = 1;
    
    /**
     * акции компании (id компании)
     */
    const TYPE_STOCKS = 2;
    
    /**
     * ресурсы (id прототипа ресурса)
     */
    const TYPE_RESOURCES = 3;
    
    /**
     * здания (id здания)
     */
    const TYPE_BUILDING = 4;
    
    /**
     * обьекты инфраструктуры (id объекта)
     */
    const TYPE_BUILDING_TWOTILED = 5;
    
    /**
     * движимые объекты (id объекта)
     */
    const TYPE_UNIT = 6;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealings-items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dealingId', 'type', 'protoId', 'count'], 'required'],
            [['dealingId', 'type', 'protoId', 'subProtoId', 'quality', 'deterioration', 'locationId'], 'integer', 'min' => 0],
            [['count'], 'number', 'min' => 0],
            [['direction'], 'boolean'],
            [['dealingId'], 'exist', 'skipOnError' => true, 'targetClass' => Dealing::className(), 'targetAttribute' => ['dealingId' => 'id']],
        ];
    }
    
    public function beforeSave($insert)
    {
        if ($insert) {
            if (!$this->count) {
                $this->count = 1;
            }
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getDealing()
    {
        return $this->hasOne(Dealing::classname(), ['id' => 'dealingId']);
    }
}