<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "bill_types_fields".
 *
 * @property integer $bill_id
 * @property string $name
 * @property string $system_name
 * @property string $type
 */
class BillTypeField extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_types_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_id', 'name', 'system_name', 'type'], 'required'],
            [['bill_id'], 'integer'],
            [['name'], 'string', 'max' => 1000],
            [['system_name', 'type'], 'string', 'max' => 255]/*,
            [['system_name'], 'unique']*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bill_id' => 'Bill ID',
            'name' => 'Name',
            'system_name' => 'System Name',
            'type' => 'Type',
        ];
    }
}
