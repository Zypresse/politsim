<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "factory_categories".
 *
 * @property integer $id
 * @property string $name
 */
class FactoryCategory extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_categories';
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