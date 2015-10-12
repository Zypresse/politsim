<?php

namespace app\models;

use app\components\MyModel;

/**
 * This is the model class for table "vacansies".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $region_id
 * @property integer $pop_class_id
 * @property integer $count_need
 * @property integer $count_all
 * @property double $salary
 * 
 * @property factories\Factory $factory
 * @property Region $region
 * @property PopClass $popClass
 */
class Vacansy extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vacansies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'region_id', 'pop_class_id', 'count_need', 'count_all', 'salary'], 'required'],
            [['factory_id', 'region_id', 'pop_class_id', 'count_need', 'count_all'], 'integer'],
            [['salary'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'region_id' => 'Region ID',
            'pop_class_id' => 'Pop Class ID',
            'count_need' => 'Count Need',
            'count_all' => 'Count All',
            'salary' => 'Salary',
        ];
    }
    
    
    public function getFactory()
    {
        return $this->hasOne('app\models\factories\Factory', array('id' => 'factory_id'));
    }
    
    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }
    
    public function getPopClass()
    {
        return $this->hasOne('app\models\PopClass', array('id' => 'pop_class_id'));
    }
}