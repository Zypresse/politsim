<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\State,
    app\models\Agency,
    app\models\AgencyPost;

/**
 * 
 */
class StateController extends MyController
{
    
    public function actionIndex($id)
    {
        
        $state = State::findByPk($id);
        if (is_null($state)) {
            return $this->_r(Yii::t('app', 'State not found'));
        }
                
        return $this->render('view', [
            'state' => $state,
            'user' => $this->user
        ]);
    }
    
    public function actionConstitution($id)
    {
        
        $state = State::findByPk($id);
        if (is_null($state)) {
            return $this->_r(Yii::t('app', 'State not found'));
        }
        
        if (!$state->constitution) {
            return $this->_r(Yii::t('app', 'State have not constitution'));
        }
                
        return $this->render('constitution', [
            'state' => $state,
            'constitution' => $state->constitution,
            'user' => $this->user
        ]);
    }
    
    public function actionAgency($id)
    {
        
        $agency = Agency::findByPk($id);
        if (is_null($agency)) {
            return $this->_r(Yii::t('app', 'Agency not found'));
        }
        
        return $this->render('agency', [
            'agency' => $agency,
            'user' => $this->user
        ]);
    }
    
}
