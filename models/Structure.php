<?php

namespace app\models;

use app\components\MyModel;

/**
 * Государственная структура (унитарная, федеративная и проч.). Таблица "structures".
 *
 * @property integer $id
 * @property string $name Название гос. структуры
 */
class Structure extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structures';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100]
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
