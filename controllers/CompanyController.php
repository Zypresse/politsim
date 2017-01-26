<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\economics\Company;

/**
 * 
 */
final class CompanyController extends MyController
{
    
    public function actionView($id)
    {
        $company = $this->getCompany($id);
        
        return $this->render('view', [
            'company' => $company,
            'user' => $this->user,
        ]);
    }
    
    private function getCompany(int $id)
    {
        $company = Company::findByPk($id);
        if (is_null($company)) {
            throw new NotFoundHttpException(Yii::t('app', 'Company not found'));
        }
        return $company;
    }
    
}
