<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "cores_regions".
 *
 * @property integer $id
 * @property integer $core_id
 * @property integer $region_id
 */
class CoreRegion extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cores_regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['core_id', 'region_id'], 'required'],
            [['core_id', 'region_id'], 'integer'],
            [['core_id', 'region_id'], 'unique', 'targetAttribute' => ['core_id', 'region_id'], 'message' => 'The combination of Core ID and Region ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'core_id' => 'Core ID',
            'region_id' => 'Region ID',
        ];
    }

    public function getCores() {
        return $this->hasMany('app\models\CoreCountry', ['id' => 'core_id'])
          ->viaTable('cores_regions', ['region_id' => 'id']);
    }
}