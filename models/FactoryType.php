<?php

namespace app\models;

use app\components\MyModel;

/**
 * Тип фабрики. Таблица "factory_types".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property string $system_name
 * @property integer $category_id
 * @property integer $can_build_npc
 */
class FactoryType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'level', 'system_name', 'category_id'], 'required'],
            [['level', 'category_id', 'can_build_npc'], 'integer'],
            [['name', 'system_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'level' => 'Level',
            'system_name' => 'System Name',
            'category_id' => 'Category ID',
            'can_build_npc' => 'Can Build Npc',
        ];
    }
    
    public function getCategory()
    {
        return $this->hasOne('app\models\FactoryCategory', array('id' => 'category_id'));
    }
    
    public function getExport()
    {
        return $this->hasMany('app\models\FactoryKit', array('type_id' => 'id'))->where(['direction'=>2]);
    }
    public function getImport()
    {
        return $this->hasMany('app\models\FactoryKit', array('type_id' => 'id'))->where(['direction'=>1]);
    }
}