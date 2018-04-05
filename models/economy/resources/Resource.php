<?php

namespace app\models\economy\resources;

use app\models\base\PassiveRecord;
use app\models\economy\resources\types;

/**
 * Description of Resource
 *
 * @author ilya
 * 
 * @property string $name
 */
abstract class Resource extends PassiveRecord
{
    
    const OIL = 1;
    
    /**
     * 
     * @return array
     */
    protected static function getList()
    {
        return [
            self::OIL => [
                'className' => types\Oil::class,
            ],
        ];
    }
    
    /**
     * @return string
     */
    abstract function getName(): string;

}
