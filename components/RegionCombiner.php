<?php

namespace app\components;

use app\models\politics\Region;

/**
 * Объединитель регионов
 *
 * @author dev
 */
class RegionCombiner extends TileCombiner
{
    
    private static function getLineIdByPoints($point1, $point2)
    {
        foreach (static::$lines as $i => $line) {
            if (
                    (static::pointsEquals($point1, $line[0]) && static::pointsEquals($point2, $line[1]))
                 || (static::pointsEquals($point2, $line[0]) && static::pointsEquals($point1, $line[1]))
            ) {
                return $i;
            }
        }
        return null;
    }


    public static function combine(\yii\db\ActiveQuery $query)
    {
        static::$conturs = [];
        static::$lines = [];
        static::$linesAdded = [];
        
        /* @var $list Region[] */
        $list = $query->all();
        $count = $query->count();
        if ($count == 0) {
            return [];
        }
        $conturs = [];
        foreach ($list as $region) {
            $polygons = json_decode($region->getPolygon());
            foreach ($polygons as $polygon) {
                $conturs[] = $polygon;
            }
        }
        unset($list);
        
        static::$lines = [];
        
        foreach ($conturs as $i => $contur) {
            $lastElement = count($contur)-1;
            foreach ($contur as $j => $point) {
                $next = $j == $lastElement ? 0 : $j+1;
                $point1 = static::pointToInt($point);
                $point2 = static::pointToInt($contur[$next]);
                $lineId = static::getLineIdByPoints($point1, $point2);
                if ($lineId) {
                    unset(static::$lines[$lineId]);
                } else {
                    static::$lines[] = [$point1, $point2];
                }
            }
        }
        unset($conturs);        
                
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
