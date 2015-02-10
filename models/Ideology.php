<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "ideologies".
 *
 * @property integer $id
 * @property string $name
 * @property integer $d
 */
class Ideology extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ideologies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'd'], 'required'],
            [['d'], 'integer'],
            [['name'], 'string', 'max' => 300]
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
            'd' => 'Уровень \"правости\" 0 — коммунизм, 50 социал-либерализм, 100 — фашизм',
        ];
    }
}
