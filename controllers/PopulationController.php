<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\State,
    app\models\Region,
    app\models\City;

/**
 * 
 */
class PopulationController extends MyController {
    
    public function actionState($id)
    {
        $state = $this->getState($id);
        
        return $this->render('state', [
            'state' => $state,
        ]);
    }
    
    public function actionRegion($id)
    {
        $region = $this->getRegion($id);
        
        return $this->render('region', [
            'region' => $region,
        ]);
    }
    
    public function actionCity($id)
    {
        $city = $this->getCity($id);
        
        return $this->render('city', [
            'city' => $city,
        ]);
    }
    
    /**
     * 
     * @param integer $id
     * @return State
     * @throws NotFoundHttpException
     */
    private function getState($id)
    {
        $state = State::findByPk($id);
        if (is_null($state)) {
            throw new NotFoundHttpException(Yii::t('app', 'State not found'));
        }
        return $state;
    }
    
    /**
     * 
     * @param integer $id
     * @return Region
     * @throws NotFoundHttpException
     */
    private function getRegion($id)
    {
        $region = Region::findByPk($id);
        if (is_null($region)) {
            throw new NotFoundHttpException(Yii::t('app', 'Region not found'));
        }
        return $region;
    }
    
    /**
     * 
     * @param integer $id
     * @return City
     * @throws NotFoundHttpException
     */
    private function getCity($id)
    {
        $city = City::findByPk($id);
        if (is_null($city)) {
            throw new NotFoundHttpException(Yii::t('app', 'City not found'));
        }
        return $city;
    }
}
