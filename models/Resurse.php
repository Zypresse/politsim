<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "resurses".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $level
 */
class Resurse extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'resurses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'level'], 'required'],
            [['level'], 'integer'],
            [['code'], 'string', 'max' => 100],
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
            'code' => 'Code',
            'name' => 'Name',
            'level' => 'Level',
        ];
    }
}
