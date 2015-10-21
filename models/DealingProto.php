<?php

namespace app\models;

use app\components\MyModel;

/**
 * Типы сделок. Таблица "dealing_prototypes".
 *
 * @property integer $id
 * @property string $name
 */
class DealingProto extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealing_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
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
        ];
    }
}