<?php

namespace app\models\economics;

/**
 * 
 */
trait BaseSubtypesResource
{
    
    public static function loadSubtype($subId = null)
    {
        return static::findByPk($subId);
    }
}
