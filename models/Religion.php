<?php

namespace app\models;

use app\components\MyModel;

/**
 * Религии населения и игроков. Таблица "religions".
 *
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property double $aggression
 */
class Religion extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'religions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'color', 'aggression'], 'required'],
            [['name', 'color'], 'string'],
            [['aggression'], 'number']
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
            'color' => 'Color',
            'aggression' => 'Aggression',
        ];
    }
}