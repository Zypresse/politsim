<?php

namespace app\models\politics\constitution\templates;

use app\models\politics\State;

/**
 * 
 */
abstract class ConstitutionGenerator
{

    /**
     *
     * @var ConstitutionTemplate
     */
    private static $template;
    
    public static function generate(State &$state, $templateClass, $params = [])
    {
        static::$template = new $templateClass;
        static::$template->generate($state, $params);
    }
    
}
