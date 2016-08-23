<?php

namespace app\models\factories\proto;

use app\components\MyModel,
    app\models\factories\proto\FactoryProto,
    app\models\PopClass;

/**
 * Необходимый класс и число рабочих для типа фабрики. Таблица "factories_prototypes_workers".
 *
 * @property integer $id
 * @property integer $proto_id
 * @property integer $pop_class_id
 * @property integer $count
 * 
 * @property FactoryProto $proto Тип фабрики
 * @property PopClass $popClass Класс населения
 */
class FactoryProtoWorker extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factories_prototypes_workers';
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
        return $this->hasOne(FactoryProto::className(), array('id' => 'proto_id'));
    }    
    
    public function getPopClass()
    {
        return $this->hasOne(PopClass::className(), array('id' => 'pop_class_id'));
    }
}