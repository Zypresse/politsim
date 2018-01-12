<?php

namespace app\models\economics\resources\base;

use app\models\base\MyActiveRecord,
    app\models\economics\ResourceProtoInterface;

/**
 * 
 */
abstract class SubtypesResourceProto extends MyActiveRecord implements ResourceProtoInterface
{
    
    public static function loadSubtype($subId = null)
    {
        return static::findByPk($subId);
    }
    
}
