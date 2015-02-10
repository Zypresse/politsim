<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "states".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $capital
 * @property string $color
 * @property integer $legislature
 * @property integer $executive
 * @property integer $state_structure
 * @property integer $goverment_form
 * @property integer $group_id
 */
class State extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'capital'], 'required'],
            [['legislature', 'executive', 'state_structure', 'goverment_form', 'group_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['short_name'], 'string', 'max' => 10],
            [['capital', 'color'], 'string', 'max' => 7]
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
            'short_name' => 'Short Name',
            'capital' => 'Capital',
            'color' => 'Color',
            'legislature' => 'Legislature',
            'executive' => 'Executive',
            'state_structure' => 'State Structure',
            'goverment_form' => 'Goverment Form',
            'group_id' => 'Group ID',
        ];
    }
}
