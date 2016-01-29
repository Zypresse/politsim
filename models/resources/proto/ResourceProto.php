<?php

namespace app\models\resources\proto;

use app\components\MyModel,
    app\components\MyHtmlHelper;

/**
 * Тип ресурса. Таблица "resources_prototypes".
 *
 * @property integer $id
 * @property string $class_name Класс ресурса (напр. "Oil")
 * @property string $name Имя ресурса (напр. "Нефть")
 * @property integer $level Уровень ресурса (0 - добываемые, 1 - переработанные, 2 - отходы, 3 - люди, 4 - временные ресурсы)
 * @property double $market_cost Рыночная цена
 * 
 * @property string $icon
 */
class ResourceProto extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resources_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name', 'level', 'name'], 'required'],
            [['level'], 'integer'],
            [['market_cost'], 'number'],
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
            'market_cost' => 'Market Cost',
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
    
    public function getHtmlName()
    {
        return $this->icon.' '.$this->name;
    }
    
    public function getIcon()
    {
        return MyHtmlHelper::customIcon('resources/'.$this->class_name,  $this->name);
    }

}
