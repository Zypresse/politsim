<?php

namespace app\models;

use app\components\MyModel;

/**
 * Установленные зарплаты для рабочих. Таблица "factory_workers_salary".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $pop_class_id
 * @property double $salary
 * 
 * @property Factory $factory
 * @property PopClass $popClass
 */
class FactoryWorkersSalary extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_workers_salary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'pop_class_id', 'salary'], 'required'],
            [['factory_id', 'pop_class_id'], 'integer'],
            [['salary'], 'number'],
            [['factory_id', 'pop_class_id'], 'unique', 'targetAttribute' => ['factory_id', 'pop_class_id'], 'message' => 'The combination of Factory ID and Pop Class ID has already been taken.']
        ];
    }
    
    public function getFactory()
    {
        return $this->hasOne('app\models\Factory', array('id' => 'factory_id'));
    }
    
    public function getPopClass()
    {
        return $this->hasOne('app\models\PopClass', array('id' => 'pop_class_id'));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'pop_class_id' => 'Pop Class ID',
            'salary' => 'Salary',
        ];
    }
    
    
}