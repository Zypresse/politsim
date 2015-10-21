<?php

namespace app\models\resurses\proto;

use app\components\MyModel;

/**
 * Тип ресурса. Таблица "resurses_prototypes".
 *
 * @property integer $id
 * @property string $class_name Класс ресурса (напр. "Oil")
 * @property string $name Имя ресурса (напр. "Нефть")
 * @property integer $level Уровень ресурса (0 - добываемые, 1 - переработанные, 2 - отходы, 3 - люди, 4 - временные ресурсы)
 */
class ResurseProto extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resurses_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'class_name', 'level'], 'required'],
            [['level'], 'integer'],
            [['class_name', 'name'], 'string'],
            [['class_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'class_name' => 'Class Name', 
            'name' => 'Name', 
        ];
    }
    
    /**
     * Добываемые (нефть, руда, зерно)
     */
    const LEVEL_ZERO = 0;
    
    /**
     * Переработанные (бензин, сталь, мука, хлеб)
     */
    const LEVEL_ONE = 1;
    
    /**
     * Отходы (шлак, отвалы)
     */
    const LEVEL_DUMP = 2;
    
    /**
     * Нехранимые (электричество)
     */
    const LEVEL_NOTSTORED = 4;
    
    /**
     * 
     * @return boolean
     */
    public function isStorable()
    {
        return $this->level !== static::LEVEL_NOTSTORED;
    }

}
