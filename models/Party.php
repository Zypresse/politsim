<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "parties".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $image
 * @property integer $state_id
 * @property integer $leader
 * @property integer $ideology
 * @property integer $group_id
 * @property integer $star
 * @property integer $heart
 * @property integer $chart_pie
 */
class Party extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'state_id', 'leader', 'ideology'], 'required'],
            [['state_id', 'leader', 'ideology', 'group_id', 'star', 'heart', 'chart_pie'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['short_name'], 'string', 'max' => 30],
            [['image'], 'string', 'max' => 1000]
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
            'image' => 'Image',
            'state_id' => 'State ID',
            'leader' => 'Leader',
            'ideology' => 'Ideology',
            'group_id' => 'Group ID',
            'star' => 'Star',
            'heart' => 'Heart',
            'chart_pie' => 'Chart Pie',
        ];
    }
}
