<?php

namespace app\models\economics\resources\base;

use yii\base\Model,
    app\models\economics\ResourceProtoInterface;

/**
 * 
 */
abstract class NoSubtypesResourceProto extends Model implements ResourceProtoInterface
{
    
    /**
     * ID субпрототипа
     * @var integer
     */
    public $id;
    
    public static function loadSubtype($subId = null)
    {
        return new static([
            'id' => $subId,
        ]);
    }
    
}
