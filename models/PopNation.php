<?php

namespace app\models;

use app\components\MyModel;

/**
 * Нация. Таблица "pop_nations".
 *
 * @property integer $id
 * @property string $name Название
 * @property integer $group_id ID группы наций
 */
class PopNation extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pop_nations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'group_id'], 'required'],
            [['group_id'], 'integer'],
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
            'group_id' => 'группа национальностей (алтайская, финно-угорская и т.д.)',
        ];
    }
}
