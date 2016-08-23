<?php

namespace app\models\tiles;

use app\models\tiles\Tile;

/**
 * Description of TileFactory
 *
 * @author i.gorohov
 */
class TileFactory {
    
    const OUTER_RADIUS = 0.1;
    const INNER_RADIUS = 0.0866;

    /**
     * 
     * @param int $x
     * @param int $y
     * @param boolean $isWater
     * @param boolean $isLand
     * @param boolean $isMountains
     * @return Tile
     */
    public static function generate($x, $y, $isWater = true, $isLand = false, $isMountains = false)
    {
        $lat = static::getLat($x, $y);
        $lng = static::getLng($x, $y);
        
        $tile = new Tile([
            'x' => $x,
            'y' => $y,
            'is_water' => $isWater ? 1 : 0,
            'is_land' => $isLand ? 1 : 0,
            'is_mountains' => $isMountains ? 1 : 0,
            'center_lat' => $lat,
            'center_lng' => $lng
        ]);
        
        return $tile;
    }
    
    /**
     * 
     * @param int $x
     * @param int $y
     * @return double
     */
    public static function getLat($x, $y)
    {
        $lat = ($y%2 === 0) ? 0.0 : static::INNER_RADIUS;
        if ($x > 0) {
            for ($i = 0; $i < $x; $i++) {
                $lat += static::INNER_RADIUS*2*static::correctLat($lat);
            }
        } else {
            for ($i = 0; $i > $x; $i--) {
                $lat -= static::INNER_RADIUS*2*static::correctLat($lat);
            }
        }
        return $lat;
    }
    
    /**
     * 
     * @param int $x
     * @param int $y
     * @return double
     */
    public static function getLng($x, $y)
    {
        return $y*static::OUTER_RADIUS*1.5;
    }
    
    /**
     * 
     * @param int $lat
     * @return double
     */
    public static function correctLat($lat)
    {
        return cos($lat*0.0175)*41000/360 / 111.1111;
    }
            
}
