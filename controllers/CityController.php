<?php

namespace app\controllers;

use app\controllers\base\MyController,
    app\models\politics\City;

/**
 * 
 */
final class CityController extends MyController
{
    
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
    
    public function actionConstitutionValue(int $cityId, int $type)
    {
        $city = $this->getRegion($cityId);
        $article = $city->constitution->getArticleByTypeOrEmptyModel($type);
        $this->result = $article->getPublicAttributes();
        return $this->_r();
    }
}
