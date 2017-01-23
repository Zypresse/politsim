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
    const WORK = 2;
    
    public static function getClassNameByType(int $id)
    {
        $classes = [
            static::MONEY => 'Currency',
            static::WORK => 'Work',
        ];
        
        return 'app\\models\\economics\\resources\\'.$classes[$id];
    }
    
    public static function getPrototype(int $id, int $subId)
    {
        $className = static::getClassNameByType($id);
        return $className::loadSubtype($subId);
    }
    
}
