<?php

namespace app\controllers;

use app\components\MyController,
    app\models\politics\Agency;

/**
 * 
 */
class AgencyController extends MyController
{
    
    public function actionConstitutionValue(int $agencyId, int $type)
    {
        $agency = $this->getRegion($agencyId);
        $article = $agency->constitution->getArticleByTypeOrEmptyModel($type);
        $this->result = $article->getPublicAttributes();
        return $this->_r();
    }
}
