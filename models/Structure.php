<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "structures".
 *
 * @property integer $id
 * @property string $name
 */
class Structure extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structures';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100]
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
