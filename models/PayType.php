<?php

namespace app\models;

use app\components\MyModel;

/**
 * Типы сделок. Таблица "paytypes".
 *
 * @property integer $id
 * @property string $name
 */
class PayType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paytypes';
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