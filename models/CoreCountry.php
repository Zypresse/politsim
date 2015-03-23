<?php

namespace app\models;

use app\components\MyModel;

/**
 * Историческая страна, имеющая щитки. Таблица "cores_countries".
 *
 * @property integer $id
 * @property string $name
 * 
 * @property Region[] $regions Регионы
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