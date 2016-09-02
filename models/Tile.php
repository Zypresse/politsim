<?php

namespace app\models;

use Yii,
    app\components\MyModel;

/**
 * Тайо
 * 
 * @property integer $id 
 * @property integer $x 
 * @property integer $y 
 * @property double $lat
 * @property double $lon
 * @property boolean $isWater
 * @property boolean $isLand
 * @property integer $population
 * @property integer $regionId
 * @property integer $cityId
 * 
 * @property Region $regionId
 * @property City $cityId
 *
 * @author ilya
 */
class Tile extends MyModel
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiles';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'lat', 'lon', 'isWater', 'isLand', 'population'], 'required'],
            [['population', 'regionId', 'cityId'], 'integer', 'min' => 0],
            [['lat', 'lon'], 'number'],
            [['isWater', 'isLand'], 'boolean'],
        ];
    }
        
}
