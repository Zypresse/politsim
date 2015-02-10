<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "goverment_field_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $system_name
 * @property string $type
 * @property integer $hide
 * @property string $default
 */
class GovermentFieldType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goverment_field_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'system_name', 'type', 'default_value'], 'required'],
            [['hide'], 'integer'],
            [['name', 'default_value'], 'string', 'max' => 1000],
            [['system_name', 'type'], 'string', 'max' => 255]
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
            'system_name' => 'System Name',
            'type' => 'Type',
            'hide' => 'Hide',
            'default_value' => 'Default',
        ];
    }

    private $publicAttributes = [
        'id',
        'name',
        'system_name',
        'type',
        'default_value'
    ];
}
