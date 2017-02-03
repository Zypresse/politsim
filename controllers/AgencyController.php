<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\controllers\base\MyController,
    app\models\politics\Agency;

/**
 * 
 */
final class AgencyController extends MyController
{
    
    public function actionConstitutionValue(int $agencyId, int $type)
    {
        $agency = $this->getAgency($agencyId);
        $article = $agency->constitution->getArticleByTypeOrEmptyModel($type);
        $this->result = $article->getPublicAttributes();
        return $this->_r();
    }
    
    private function getAgency(int $id)
    {
        $agency = Agency::findByPk($id);
        if (is_null($agency)) {
            throw new NotFoundHttpException(Yii::t('app', 'Agency not found'));
        }
        return $agency;
    }
}
