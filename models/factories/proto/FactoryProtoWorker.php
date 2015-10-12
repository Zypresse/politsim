<?php

namespace app\models\factories\proto;

use app\components\MyModel;

/**
 * Необходимый класс и число рабочих для типа фабрики. Таблица "factory_prototypes_workers".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $pop_class_id
 * @property integer $count
 * 
 * @property FactoryProto $proto Тип фабрики
 * @property \app\models\PopClass $popClass Класс населения
 */
class FactoryProtoWorker extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_prototypes_workers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proto_id', 'pop_class_id', 'count'], 'required'],
            [['proto_id', 'pop_class_id', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'proto_id' => 'Type ID',
            'pop_class_id' => 'Pop Class ID',
            'count' => 'Count',
        ];
    }
    
    
    public function getType()
    {
        return $this->hasOne('app\models\factories\proto\FactoryProto', array('id' => 'proto_id'));
    }    
    
    public function getPopClass()
    {
        return $this->hasOne('app\models\PopClass', array('id' => 'pop_class_id'));
    }
}