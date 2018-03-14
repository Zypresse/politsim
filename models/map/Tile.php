<?php

namespace app\models\map;

use Yii;
use app\models\base\ActiveRecord;

/**
 * This is the model class for table "tiles".
 *
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $lat
 * @property integer $lon
 * @property integer $biome
 * @property integer $population
 * @property integer $regionId
 * @property integer $cityId
 * @property integer $districtId
 *
 * @property City $city
 * @property Region $region
 * @property User[] $users
 */
class Tile extends ActiveRecord
{

    /**
     * На столько умножаются координаты тайла для округления
     */
    const LAT_LON_FACTOR = 10000;
    
    /**
     * mask is water
     */
    const BIOME_WATER = 1;
    
    /**
     * mask is land
     */
    const BIOME_LAND = 2;
    
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
            [['x', 'y', 'lat', 'lon'], 'required'],
            [['x', 'y', 'lat', 'lon', 'population', 'regionId', 'cityId', 'districtId'], 'default', 'value' => null],
            [['x', 'y', 'lat', 'lon', 'population', 'regionId', 'cityId', 'districtId', 'biome'], 'integer'],
            [['lat', 'lon'], 'unique', 'targetAttribute' => ['lat', 'lon']],
            [['x', 'y'], 'unique', 'targetAttribute' => ['x', 'y']],
            [['cityId'], 'exist', 'skipOnError' => false, 'targetClass' => City::className(), 'targetAttribute' => ['cityId' => 'id']],
            [['regionId'], 'exist', 'skipOnError' => false, 'targetClass' => Region::className(), 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'x' => 'X',
            'y' => 'Y',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'isWater' => 'Is Water',
            'isLand' => 'Is Land',
            'population' => 'Population',
            'regionId' => 'Region ID',
            'cityId' => 'City ID',
            'districtId' => 'District ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['tileId' => 'id']);
    }

}
