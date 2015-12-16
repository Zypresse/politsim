<?php

namespace app\models;

use app\components\MyModel,
    app\models\Region,
    app\models\State;

/**
 * Историческая страна, имеющая щитки. Таблица "cores_countries".
 *
 * @property integer $id
 * @property string $name
 * 
 * @property Region[] $regions Регионы
 * @property State[] $states
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
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }

    public function getRegions()
    {
        return $this->hasMany(Region::className(), ['id' => 'region_id'])
                ->viaTable('cores_regions', ['core_id' => 'id']);
    }
    
    public function getStates()
    {
        return $this->hasMany(State::className(),['core_id' => 'id']);
    }

}
