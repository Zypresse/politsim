<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "elect_results".
 *
 * @property integer $id
 * @property integer $org_id
 * @property integer $leader
 * @property integer $date
 * @property string $data
 */
class ElectResult extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elect_results';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id', 'date', 'data'], 'required'],
            [['org_id', 'leader', 'date'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'leader' => 'Leader',
            'date' => 'Date',
            'data' => 'Data',
        ];
    }
    
    public function getOrg()
    {
        return $this->hasOne('app\models\Org', array('id' => 'org_id'));
    }
}