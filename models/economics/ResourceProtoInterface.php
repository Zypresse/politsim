<?php

namespace app\models\economics;

/**
 *
 * @property string $name
 * @property string $icon
 * 
 */
interface ResourceProtoInterface
{
    
    public static function loadSubtype($subId = null);
    
    public function getName();
    
    public function getIcon();
    
}
