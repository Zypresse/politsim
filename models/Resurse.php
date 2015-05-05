<?php

namespace app\models;

use app\components\MyModel;

/**
 * Тип ресурса. Таблица "resurses".
 *
 * @property integer $id
 * @property string $code Код ресурса (напр. "oil")
 * @property string $name Имя ресурса (напр. "Нефть")
 * @property integer $level Уровень ресурса (0 - добываемые, 1 - переработанные, 2 - конечный продукт, 3 - люди, 4 - временные ресурсы)
 */
class Resurse extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resurses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'level'], 'required'],
            [['level'], 'integer'],
            [['code'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'    => 'ID',
            'code'  => 'Code',
            'name'  => 'Name',
            'level' => 'Level',
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

}
