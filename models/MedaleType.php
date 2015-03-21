<?php

namespace app\models;

use app\components\MyModel;

/**
 * Тип значка. Таблица "medales_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property string $image
 */
class MedaleType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medales_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'desc', 'image'], 'required'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 300]
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
            'desc' => 'Desc',
            'image' => 'Image',
        ];
    }
}
