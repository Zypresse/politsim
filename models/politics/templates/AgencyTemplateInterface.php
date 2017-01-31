<?php

namespace app\models\politics\templates;

/**
 * 
 */
interface AgencyTemplateInterface
{
    
    public static function create(int $stateId, $params);
    
}
