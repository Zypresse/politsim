<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "cores_countries".
 *
 * @property integer $id
 * @property string $name
 */
class CoreCountry extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cores_countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
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
    

    public function getRegions() {
        return $this->hasMany('app\models\Region', ['id' => 'region_id'])
          ->viaTable('cores_regions', ['core_id' => 'id']);
    }
}