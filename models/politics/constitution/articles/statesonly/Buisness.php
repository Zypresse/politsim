<?php

namespace app\models\politics\constitution\articles\statesonly;

use app\models\politics\constitution\articles\base\CheckboxArticle;

/**
 * 
 */
class Buisness extends CheckboxArticle
{
    
    public static function instantiate($row)
    {
        if (!$row['subType']) {
            return new static($row);
        }
        
        $className = '\\app\\models\\politics\\constitution\\articles\\statesonly\\buisness\\'.([
            //
        ][(int) $row['subType']]);
        return $className::instantiate($row);
    }

}
