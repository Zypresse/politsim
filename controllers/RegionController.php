<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\politics\Region;

/**
 * 
 */
class RegionController extends MyController {
    
    public function actionIndex($id)
    {
        
        $region = Region::findByPk($id);
        if (is_null($region)) {
            return $this->_r(Yii::t('app', 'Region not found'));
        }
        
        return $this->render('view', [
            'region' => $region,
            'user' => $this->user
        ]);
    }
    
    public function actionConstitution($id)
    {
        
        $region = Region::findByPk($id);
        if (is_null($region)) {
            return $this->_r(Yii::t('app', 'Region not found'));
        }
        
        return $this->render('constitution', [
            'region' => $region,
            'constitution' => $region->constitution,
            'user' => $this->user
        ]);
    }
    
}
