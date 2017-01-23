<?php

namespace app\models\economics;

/**
 * 
 */
trait NoSubtypesResource
{
    
    public static function loadSubtype($subId = null)
    {
        return new static();
    }
    
}
