<?php

namespace app\models\economics;

/**
 * 
 */
class ResourceProto
{
    
    /**
     * Деньги
     */
    const MONEY = 1;
    
    /**
     * Условная "работа", результат труда нпц
     */
    const WORK = -2;
    
    /**
     * Акция
     */
    const SHARE = 3;
    
    /**
     * Строительство зданий
     */
    const BUILDING_CONSTRUCTING = -1;
    
    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::MONEY => 'Currency',
            static::WORK => 'Work',
            static::SHARE => 'Share',
            static::BUILDING_CONSTRUCTING => 'BuildingConstructing',
        ];
        
        return 'app\\models\\economics\\resources\\'.$classes[$id];
    }
    
    public static function getPrototype(int $id, $subId = null)
    {
        $className = static::getClassNameByType($id);
        return $className::loadSubtype($subId);
    }
    
}
