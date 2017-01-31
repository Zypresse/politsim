<?php

namespace app\models\politics\templates;

use app\models\politics\Agency;

/**
 * 
 */
class EmptyAgency implements AgencyTemplateInterface
{
    
    public static function create(int $stateId, $params)
    {
        $agency = new Agency([
            'stateId' => $stateId,
            'name' => $params['name'],
            'nameShort' => $params['nameShort'],
        ]);
        $agency->save();
        return $agency;
    }

}
