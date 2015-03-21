<?php

namespace app\models;

use app\components\MyModel;

/**
 * Фабрика/завод/сх-предприятие. Таблица "factories".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $builded
 * @property integer $holding_id
 * @property integer $region_id
 * @property integer $status Статус работы: 0 - undefined, 1 - active, 2 - stopped, 3 - not enought resurses, 4 - autostopped
 * @property string $name
 * @property integer $level
 */
class Factory extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'builded', 'name'], 'required'],
            [['type_id', 'builded', 'holding_id', 'region_id', 'status', 'level'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'builded' => 'Builded',
            'holding_id' => 'Holding ID',
            'region_id' => 'Region ID',
            'status' => '0 - undefined, 1 - active, 2 - stopped, 3 - not enought resurses, 4 - autostopped',
            'name' => 'Name',
            'level' => 'Level',
        ];
    }
    
    public function getType()
    {
        return $this->hasOne('app\models\FactoryType', array('id' => 'type_id'));
    }
    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
    }
    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }
    
}