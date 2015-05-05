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
 * @property integer $status Статус работы: -1 - unbuilded, -2 - build stopped, 0 - undefined, 1 - active, 2 - stopped, 3 - not enought resurses, 4 - autostopped, 5 - not enought workers
 * @property string $name
 * @property integer $size
 * 
 * @property FactoryType $type Тип фабрики
 * @property Holding $holding Компания-владелец
 * @property Region $region Регион, в котором она находится
 * @property FactoryWorker[] $workers Рабочие
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
            [['type_id', 'builded', 'holding_id', 'region_id', 'status', 'size'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'type_id'    => 'Type ID',
            'builded'    => 'Builded',
            'holding_id' => 'Holding ID',
            'region_id'  => 'Region ID',
            'status'     => 'Статус работы: -1 - unbuilded, -2 - build stopped, 0 - undefined, 1 - active, 2 - stopped, 3 - not enought resurses, 4 - autostopped, 5 - not enought workers',
            'name'       => 'Name',
            'size'       => 'Size',
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
    
    public function getWorkers()
    {
        return $this->hasMany('app\models\FactoryWorker', array('factory_id' => 'id'));
    }

}
