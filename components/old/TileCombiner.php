<?php

namespace app\components;

use app\models\Tile;

/**
 * Объединитель тайлов в контуры
 *
 * @author dev
 */
abstract class TileCombiner {
    
    protected static function pointToInt($point)
    {
        return [(int)($point[0]*10000), (int)($point[1]*10000)];
    }
    
    protected static function pointToFloat($point)
    {
        return [$point[0]/10000, $point[1]/10000];
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
        
    protected static $lines = [];
    protected static $conturs = [];
    protected static $linesAdded = [];
    
    protected static function pointsEquals($p1, $p2)
    {
        $d = 100;
        return (abs($p1[0]-$p2[0]) <= $d) && (abs($p1[1]-$p2[1]) <= $d);
    }


    protected static function getLineLeftAndRightIds($line)
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
    
    
    protected static function addLine($i)
    {
        if (in_array($i, static::$linesAdded)) {
            if (count(static::$linesAdded) == count(static::$lines)) {
                return -1;
            }
            foreach (static::$lines as $i => $line) {
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

        static::$conturs[] = [$i];
        return $left;
    }

    public static function combine(\yii\db\ActiveQuery $query)
    {
        /* @var $list Tile[] */
        $list = $query->all();
        return static::combineList($list);
    }
        
    /**
     * 
     * @param Tile[] $list
     * @return array
     * @throws \yii\console\Exception
     */
    public static function combineList(array $list)
    {
        $count = count($list);
        if ($count == 0) {
            return [];
        } elseif ($count > 100000) {
            throw new \yii\console\Exception("More than 100 000 tiles to combine");
        }
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
        while ($n >= 0) {
            $n = static::addLine($n);
        }
                
        foreach (static::$conturs as &$contur) {
            foreach ($contur as $i => $lineId) {
                $point1 = static::$lines[$lineId][0];           
                $contur[$i] = static::pointToFloat($point1);
            }
        }
        $result = static::$conturs;
        
        static::$conturs = [];
        static::$lines = [];
        static::$linesAdded = [];
        return $result;
    }
}
