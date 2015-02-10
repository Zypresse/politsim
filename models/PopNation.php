<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "pop_nations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $group_id
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
