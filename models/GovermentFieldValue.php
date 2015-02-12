<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "goverment_field_values".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $state_id
 * @property string $value
 */
class GovermentFieldValue extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goverment_field_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'state_id', 'value'], 'required'],
            [['type_id', 'state_id'], 'integer'],
            [['value'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'state_id' => 'State ID',
            'value' => 'Value',
        ];
    }

    public function getType()
    {
        return $this->hasOne('app\models\GovermentFieldType', array('id' => 'type_id'));
    }
}
