<?php

namespace app\models\economy\resources\types;

use Yii;
use app\models\economy\resources\Resource;

/**
 * Нефть хуефть
 *
 * @author ilya
 */
class Oil extends Resource
{
    
    public function getName(): string
    {
        return 'Нефть';
    }

}
