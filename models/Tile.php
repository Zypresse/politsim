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
    
    private static $directions = [
        [ //    nord      n-e       s-e      south      s-w       n-w
            [ [+1,  0], [ 0, +1], [-1, +1], [-1,  0], [-1, -1], [ 0, -1] ],
            [ [+1,  0], [+1, +1], [ 0, +1], [-1,  0], [ 0, -1], [+1, -1] ],
        ], [
            [ [+1,  0], [ 0, +1], [-1, +1], [-1,  0], [-1, -1], [ 0, -1] ],
            [ [+1,  0], [+1, +1], [ 0, +1], [-1,  0], [ 0, -1], [+1, -1] ]
        ]
    ];

    private static function offsetNeighbor($h, $d)
    {
        $parityX = $h[0] & 1;
        $parityY = $h[1] & 1;
        $off = static::$directions[$parityX][$parityY][$d];
        
        return [$h[0] + $off[0], $h[1] + $off[1]];
    }
    
    private static $borderIdToPointsIds = [[4,5],[5,0],[0,1],[1,2],[2,3],[3,4]];
        
    private static $lines = [];
    private static $conturs = [];
    private static $linesAdded = [];
    
    private static function pointsEquals($p1, $p2)
    {
        $d = 100;
        return (abs($p1[0]-$p2[0]) <= $d) && (abs($p1[1]-$p2[1]) <= $d);
    }


    private static function getLineLeftAndRightIds($line)
    {        
        $left = -1;
        $right = -1;
        foreach (static::$lines as $i => $currentLine) {
            
            if ($currentLine == $line)
                continue;
            
            if (static::pointsEquals($line[0],$currentLine[0]) || static::pointsEquals($line[0],$currentLine[1]))
                $left = $i;
            if (static::pointsEquals($line[1],$currentLine[0]) || static::pointsEquals($line[1],$currentLine[1]))
                $right = $i;

            if ($left > 0 && $right > 0)
                break;
        }
        return [$left, $right];
    }
    
    
    private static function addLine($i)
    {
        if (in_array($i, static::$linesAdded)) {
            if (count(static::$linesAdded) == count(static::$lines)) {
                return -1;
            }
            foreach ($lines as $i => $line) {
                if (!in_array($i, static::$linesAdded)) {
                    return $i;
                }
            }
        }

        $line = static::$lines[$i];
        static::$linesAdded[] = $i;
        list($left, $right) = static::getLineLeftAndRightIds($line);
        
        if ($left < 0 && $right < 0) {
            print("Error, line have no neighbors");
            var_dump($line);
            exit();
        } elseif ($left < 0 || $right < 0) {
            print("Error, line have only one neighbor");
            var_dump($line);
            exit();
        }
        
        foreach (static::$conturs as &$contur) {
            if (in_array($right, $contur)) {
                $contur[] = $i;
                return $left;
            }
        }

        $contur = [$i];
        static::$conturs[] = $contur;
        return $left;
    }

    public static function union(\yii\db\ActiveQuery $query)
    {
        /* @var $list static[] */
        $list = $query->all();
        $tilesByXY = [];
        foreach ($list as $tile) {
            $data = [$tile->lat, $tile->lon];
            if (isset($tilesByXY[$tile->x])) {
                $tilesByXY[$tile->x][$tile->y] = $data;
            } else {
                $tilesByXY[$tile->x] = [$tile->y => $data];
            }
        }
        unset($list);
        
        foreach ($tilesByXY as $x => $row) {
            foreach ($row as $y => $tile) {
                
                $borders = [];
                
                for ($i = 0; $i < 6; $i++) {
                    $neighbor = static::offsetNeighbor([$x, $y], $i);
                    if (!isset($tilesByXY[$neighbor[0]][$neighbor[1]])) {
                        $borders[] = $i;
                    }
                }
                
                if (count($borders)) {
                    $latFactor = (int)round(cos($tile[0]*0.0175)*887.65);
                    $lat = (int)round($tile[0]*10000);
                    $lon = (int)round($tile[1]*10000);
                    $coords = [
                        [$lat,$lon+1000], # east
                        [$lat-$latFactor,$lon+500], # east-south
                        [$lat-$latFactor,$lon-500], # west-south
                        [$lat,$lon-1000], # west
                        [$lat+$latFactor,$lon-500], # west-nord
                        [$lat+$latFactor,$lon+500] # east-nord
                    ];
                    
                    foreach ($borders as $d) {
                        list($point1, $point2) = static::$borderIdToPointsIds[$d];
                        static::$lines[] = [$coords[$point1], $coords[$point2]];
                    }
                }
            }
        }
        unset($tilesByXY);
        
        $n = 0;
        while ($n >= 0)
            $n = static::addLine($n);
        
        foreach (static::$conturs as &$contur) {
            foreach ($contur as $i => $lineId) {
                list($point1, $point2) = static::$lines[$lineId];           
                $contur[$i] = [[$point1[0]/10000,$point1[1]/10000], [$point2[0]/10000,$point2[1]/10000]];
            }
        }
        
        $result = static::$conturs;
        
        static::$conturs = [];
        static::$lines = [];
        static::$linesAdded = [];
        
        return $result;
    }
    
}
