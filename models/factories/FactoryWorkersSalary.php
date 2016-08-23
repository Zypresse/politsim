<?php

namespace app\models\factories;

use app\components\MyModel,
    app\models\factories\Factory,
    app\models\PopClass;

/**
 * Установленные зарплаты для рабочих. Таблица "factories_workers_salary".
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
        return 'factories_workers_salary';
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
        return $this->hasOne(Factory::className(), array('id' => 'factory_id'));
    }
    
    public function getPopClass()
    {
        return $this->hasOne(PopClass::className(), array('id' => 'pop_class_id'));
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