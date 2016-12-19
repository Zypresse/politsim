<?php

namespace app\models;

use Yii,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\population\Pop,
    app\models\politics\elections\ElectoralDistrict,
    app\models\base\MyActiveRecord;

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
 * @property integer $electoralDistrictId
 * 
 * @property Region $region
 * @property City $city
 * @property ElectoralDistrict $electoralDistrict
 * @property Pop[] $pops
 * 
 * @property double $latFactor
 * @property array $coords
 * @property double $radius
 * @property double $area
 *
 * @author ilya
 */
class Tile extends MyActiveRecord
{
    
    /**
     * Внешний радиус тайла в градусах
     */
    const R = 0.1;
    
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
            [['population', 'regionId', 'cityId', 'electoralDistrictId'], 'integer', 'min' => 0],
            [['lat', 'lon'], 'number'],
            [['isWater', 'isLand'], 'boolean'],
        ];
    }
    
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }
    
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }
    
    public function getElectoralDistrict()
    {
        return $this->hasOne(ElectoralDistrict::className(), ['id' => 'electoralDistrictId']);
    }
    
    public function getPops()
    {
        return $this->hasMany(Pop::className(), ['tileId' => 'id']);
    }
    
    public function getLatFactor()
    {
        return cos($this->lat*0.0175)*0.088765;
    }
    
    public function getCoords()
    {
        $latFactor = $this->latFactor;
        return [
            [$this->lat,$this->lon+static::R], # east
            [$this->lat-$latFactor,$this->lon+static::R/2], # east-south
            [$this->lat-$latFactor,$this->lon-static::R/2], # west-south
            [$this->lat,$this->lon-static::R], # west
            [$this->lat+$latFactor,$this->lon-static::R/2], # west-nord
            [$this->lat+$latFactor,$this->lon+static::R/2] # east-nord
        ];
    }
    
    private static $FUCK = [
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
    
    private function getH()
    {
        return static::$FUCK[round($this->lat)]*$this->latFactor;
    }
    
    private function getA()
    {
        return static::$FUCK[round($this->lat)]*static::R;
    }


    public function getArea()
    {
        $h = $this->getH();
        $a = $this->getA();
        
        return $h*($a*3);
    }
        
}
