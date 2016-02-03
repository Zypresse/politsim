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
            'center_lng' => $lng,
            'w_lat' => static::getWesternCoordsLat($lat),
            'w_lng' => static::getWesternCoordsLng($lng),
            'nw_lat' => static::getNorthWesternCoordsLat($lat),
            'nw_lng' => static::getNorthWesternCoordsLng($lng),
            'ne_lat' => static::getNorthEasternCoordsLat($lat),
            'ne_lng' => static::getNorthEasternCoordsLng($lng),
            'e_lat' => static::getEasternCoordsLat($lat),
            'e_lng' => static::getEasternCoordsLng($lng),
            'se_lat' => static::getSouthEasternCoordsLat($lat),
            'se_lng' => static::getSouthEasternCoordsLng($lng),
            'sw_lat' => static::getSouthWesternCoordsLat($lat),
            'sw_lng' => static::getSouthWesternCoordsLng($lng),
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
    private static function getLng($x, $y)
    {
        return $y*static::OUTER_RADIUS*1.5;
    }
    

    /**
     * 
     * @param int $lat
     * @return double
     */
    private static function correctLat($lat)
    {
        return cos($lat*0.0175)*41000/360 / 111.1111;
    }
        
    private static function getWesternCoordsLat($lat)
    {
        return $lat;
    }
    
    private static function getWesternCoordsLng($lng)
    {
        return $lng + static::OUTER_RADIUS * -1;
    }
    
    private static function getEasternCoordsLat($lat)
    {
        return $lat;
    }
    
    private static function getEasternCoordsLng($lng)
    {
        return $lng + static::OUTER_RADIUS;
    }
    
    private static function getSouthEasternCoordsLat($lat)
    {
        return $lat + static::OUTER_RADIUS * -0.866 * static::correctLat($lat);
    }
    
    private static function getSouthEasternCoordsLng($lng)
    {
        return $lng + static::OUTER_RADIUS * 0.5;
    }
    
    private static function getSouthWesternCoordsLat($lat)
    {
        return $lat + static::OUTER_RADIUS * -0.866 * static::correctLat($lat);
    }
    
    private static function getSouthWesternCoordsLng($lng)
    {
        return $lng + static::OUTER_RADIUS * -0.5;
    }
    
    private static function getNorthWesternCoordsLat($lat)
    {
        return $lat + static::OUTER_RADIUS * 0.866 * static::correctLat($lat);
    }
    
    private static function getNorthWesternCoordsLng($lng)
    {
        return $lng + static::OUTER_RADIUS * -0.5;
    }
    
    private static function getNorthEasternCoordsLat($lat)
    {
        return $lat + static::OUTER_RADIUS * 0.866 * static::correctLat($lat);
    }
    
    private static function getNorthEasternCoordsLng($lng)
    {
        return $lng + static::OUTER_RADIUS * 0.5;
    }
    
}
