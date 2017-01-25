<?php

namespace app\models\economics\resources;

use app\models\economics\resources\base\NoSubtypesResourceProto,
    app\models\economics\Company;

/**
 * Акция
 * 
 * @property Company $company
 * 
 */
final class Share extends NoSubtypesResourceProto
{
    
    public function getCompany()
    {
        return Company::findByPk($this->id);
    }
    
}
