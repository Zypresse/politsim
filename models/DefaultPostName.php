<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "default_post_names".
 *
 * @property string $type
 * @property string $name
 */
class DefaultPostName extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'default_post_names';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'name' => 'Name',
        ];
    }
}
