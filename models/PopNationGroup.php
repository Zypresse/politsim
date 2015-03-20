<?php

namespace app\models;

use app\components\MyModel;

/**
 * Категория наций. Таблица "pop_nations_groups".
 *
 * @property integer $id
 * @property string $name Название категории национальностей (напр. "славяне")
 */
class PopNationGroup extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pop_nations_groups';
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
