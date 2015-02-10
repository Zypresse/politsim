<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "population".
 *
 * @property integer $id
 * @property integer $class
 * @property integer $nation
 * @property integer $ideology
 * @property integer $sex
 * @property integer $count
 */
class Population extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'population';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class', 'nation', 'ideology', 'sex', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'ID класса (рабочие, клерки и т.д.)',
            'nation' => 'ID национальности',
            'ideology' => 'ID идеологии (0 - нейтрал)',
            'sex' => 'Пол 0 - женский, 1 - мужской',
            'count' => 'Число человек',
        ];
    }
}
