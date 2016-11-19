<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\City;

/**
 * 
 */
class CityController extends MyController {
    
    public function actionIndex($id)
    {
        $city = City::findByPk($id);
        if (is_null($city)) {
            return $this->_r("City not found");
        }
        return $this->render('view', [
            'city' => $city,
            'user' => $this->user
        ]);
    }
}
