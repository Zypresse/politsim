<?php

namespace app\models\politics\constitution\articles\base;

/**
 * 
 */
trait NoSubtypesArticle
{
    
    public static function instantiate($row)
    {
        return new static($row);
    }
    
}
