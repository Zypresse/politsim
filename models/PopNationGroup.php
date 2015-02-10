<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "pop_nations_groups".
 *
 * @property integer $id
 * @property string $name
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
