<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\Region;

/**
 * 
 */
class RegionController extends MyController {
    
    public function actionIndex($id)
    {
        $region = Region::findByPk($id);
        if (is_null($region)) {
            return $this->_r("Region not found");
        }
        return $this->render('view', [
            'region' => $region,
            'user' => $this->user
        ]);
    }
    
}
