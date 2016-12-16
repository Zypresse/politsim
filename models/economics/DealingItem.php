<?php

namespace app\models\economics;

use app\models\base\MyActiveRecord;

/**
 * Вещь, передаваемая в сделке
 *
 * @property integer $dealingId
 * @property boolean $direction see DealingItemDirection
 * @property integer $resourceId id объекта ресурса
 * @property double $count количество ресурса
 * 
 * @property Dealing $dealing
 * @property Resource $resource
 * 
 */
class DealingItem extends MyActiveRecord
{
    
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
            [['dealingId', 'resourceId', 'count'], 'required'],
            [['dealingId', 'resourceId'], 'integer', 'min' => 0],
            [['count'], 'number', 'min' => 0],
            [['direction'], 'boolean'],
            [['dealingId'], 'exist', 'skipOnError' => true, 'targetClass' => Dealing::className(), 'targetAttribute' => ['dealingId' => 'id']],
            [['resourceId'], 'exist', 'skipOnError' => true, 'targetClass' => Resource::className(), 'targetAttribute' => ['resourceId' => 'id']],
        ];
    }
    
    public function getDealing()
    {
        return $this->hasOne(Dealing::classname(), ['id' => 'dealingId']);
    }
    
    public function getResource()
    {
        return $this->hasOne(Resource::classname(), ['id' => 'dealingId']);
    }
    
}