<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "holding_licenses_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 */
class HoldingLicenseType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holding_licenses_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique']
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
            'code' => 'Code',
        ];
    }
}