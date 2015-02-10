<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "bill_types".
 *
 * @property integer $id
 * @property string $name
 * @property integer $only_auto
 * @property integer $only_dictator
 */
class BillType extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'only_auto', 'only_dictator'], 'required'],
            [['only_auto', 'only_dictator'], 'integer'],
            [['name'], 'string', 'max' => 1000]
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
            'only_auto' => 'Only Auto',
            'only_dictator' => 'Only Dictator',
        ];
    }
}
