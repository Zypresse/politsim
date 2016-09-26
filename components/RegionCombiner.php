<?php

namespace app\components;

/**
 * Объединитель регионов
 *
 * @author dev
 */
class RegionCombiner extends TileCombiner
{
        
    public static function combine(\yii\db\ActiveQuery $query)
    {
        /* @var $list \app\models\Region[] */
        $list = $query->all();
        $conturs = [];
        foreach ($list as $region) {
            $conturs += json_decode($region->getPolygon());
        }
        
        static::$lines = [];
        
        foreach ($conturs as $i => $contur) {
            $lastElement = count($contur)-1;
            foreach ($contur as $j => $point) {
                $next = $j == $lastElement ? 0 : $j+1;
                static::$lines[] = [static::pointToInt($point), static::pointToInt($contur[$next])];
            }
        }
        
        unset($list);
        
        $n = 0;
        while ($n >= 0)
            $n = static::addLine($n);
        
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
