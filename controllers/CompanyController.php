<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\economics\Utr,
    app\models\economics\Company;

/**
 * 
 */
final class CompanyController extends MyController
{
    
    public function actionView(int $id)
    {
        $company = $this->getCompany($id);
        
        return $this->render('view', [
            'company' => $company,
            'user' => $this->user,
        ]);
    }
    
    public function actionControl(int $id, int $utr)
    {
        
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        if (!$utrModel->object->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $company = $this->getCompany($id);
        
        return $this->render('control', [
            'company' => $company,
            'shareholder' => $utrModel->object,
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
