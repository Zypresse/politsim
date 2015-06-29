<?php

namespace app\models;

use app\components\MyModel;

/**
 * Необходимый класс и число рабочих для типа фабрики. Таблица "factory_type_workers".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $pop_class_id
 * @property integer $count
 * 
 * @property FactoryType $type Тип фабрики
 * @property PopClass $popClass Класс населения
 */
class FactoryTypeWorker extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_type_workers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'pop_class_id', 'count'], 'required'],
            [['type_id', 'pop_class_id', 'count'], 'integer']
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
            'pop_class_id' => 'Pop Class ID',
            'count' => 'Count',
        ];
    }
    
    
    public function getType()
    {
        return $this->hasOne('app\models\FactoryType', array('id' => 'type_id'));
    }    
    
    public function getPopClass()
    {
        return $this->hasOne('app\models\PopClass', array('id' => 'pop_class_id'));
    }
}