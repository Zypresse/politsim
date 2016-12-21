<?php

namespace app\models\politics\constitution\templates;

use yii\base\Object,
    app\models\politics\State;

/**
 * 
 */
abstract class ConstitutionTemplate extends Object
{
    
    abstract public static function generate(State &$state, $params = []);

}
