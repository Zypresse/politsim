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
 * 
 * @property double $degLat
 * @property double $degLon
 * @property array $coords
 * @property double $area
 */
class Tile extends ActiveRecord
{

    /**
     * На столько умножаются координаты тайла для округления
     */
    const LAT_LON_FACTOR = 10000;
    
    /**
     * type water
     */
    const BIOME_WATER = 1;
    
    /**
     * type land
     */
    const BIOME_LAND = 2;
    
    /**
     * В градусах
     */
    const RADIUS = 0.1;
    
    /**
     * Коэффициент уменьшения площади тайла
     */
    const AREA_FACTOR = [
        0 => 111.321,
        1 => 111.305,
        2 => 111.254,
        3 => 111.170,
        4 => 111.052,
        5 => 110.901,
        6 => 110.716,
        7 => 110.497,
        8 => 110.245,
        9 => 109.960,
        10 => 109.641,
        11 => 109.289,
        12 => 108.904,
        13 => 108.487,
        14 => 108.036,
        15 => 107.552,
        16 => 107.036,
        17 => 106.488,
        18 => 105.907,
        19 => 105.294,
        20 => 104.649,
        21 => 103.972,
        22 => 103.264,
        23 => 102.524,
        24 => 101.753,
        25 => 100.952,
        26 => 100.119,
        27 => 99.257,
        28 => 98.364,
        29 => 97.441,
        30 => 96.488,
        31 => 95.506,
        32 => 94.495,
        33 => 93.455,
        34 => 92.386,
        35 => 91.290,
        36 => 90.165,
        37 => 89.013,
        38 => 87.834,
        39 => 86.628,
        40 => 85.395,
        41 => 84.137,
        42 => 82.852,
        43 => 81.542,
        44 => 80.208,
        45 => 78.848,
        46 => 77.465,
        47 => 76.057,
        48 => 74.627,
        49 => 73.173,
        50 => 71.697,
        51 => 70.199,
        52 => 68.679,
        53 => 67.138,
        54 => 65.577,
        55 => 63.995,
        56 => 62.394,
        57 => 60.773,
        58 => 59.134,
        59 => 57.476,
        60 => 55.801,
        61 => 54.108,
        62 => 52.399,
        63 => 50.674,
        64 => 48.933,
        65 => 47.176,
        66 => 45.405,
        67 => 43.621,
        68 => 41.822,
        69 => 40.011,
        70 => 38.187,
        71 => 36.352,
        72 => 34.505,
        73 => 32.647,
        74 => 30.780,
        75 => 28.902,
        76 => 27.016,
        77 => 25.122,
        78 => 23.219,
        79 => 21.310,
        80 => 19.394,
        81 => 17.472,
        82 => 15.544,
        83 => 13.612,
        84 => 11.675,
        85 => 9.735,
        86 => 7.791,
        87 => 5.846,
        88 => 3.898,
        89 => 1.949,
        90 => 0,
    ];
    
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
            'biome' => 'Biome',
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

    /**
     * 
     * @return double
     */
    public function getDegLat()
    {
        return $this->lat/self::LAT_LON_FACTOR;
    }
    
    /**
     * 
     * @return double
     */
    public function getDegLon()
    {
        return $this->lon/self::LAT_LON_FACTOR;
    }
    
    /**
     * 
     * @return double
     */
    protected function getLatFactor()
    {
        return cos($this->degLat*0.0175)*0.088765;
    }
    
    /**
     * 
     * @return array
     */
    public function getCoords()
    {
        $latFactor = $this->getLatFactor();
        return [
            [$this->degLat,$this->degLon+self::RADIUS], # east
            [$this->degLat-$latFactor,$this->degLon+self::RADIUS/2], # east-south
            [$this->degLat-$latFactor,$this->degLon-self::RADIUS/2], # west-south
            [$this->degLat,$this->degLon-self::RADIUS], # west
            [$this->degLat+$latFactor,$this->degLon-self::RADIUS/2], # west-nord
            [$this->degLat+$latFactor,$this->degLon+self::RADIUS/2] # east-nord
        ];
    }
    
    /**
     * Hex area
     * @return double
     */
    public function getArea()
    {
        $h = self::AREA_FACTOR[abs(round($this->degLat))] * $this->getLatFactor();
        $a = self::AREA_FACTOR[abs(round($this->degLat))] * self::RADIUS;
        return $h*($a*3);
    }

}
