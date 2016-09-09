<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\State,
    app\models\Citizenship;

/**
 * 
 */
class CitizenshipController extends MyController {
    
    public function actionRequest($stateId)
    {
        $state = State::findByPk($stateId);
        if (is_null($state)) {
            return $this->_r('State not found');
        }
        
        $citizenship = new Citizenship([
            'userId' => $this->user->id,
            'stateId' => $state->id
        ]);
        
        // @TODO: принятие запросов на гражданство
        $citizenship->dateApproved = time();
        
        if ($citizenship->save()) {
            return $this->_rOk();
        } else {
            return $this->_r($citizenship->getErrors());
        }
    }
    
    public function actionCancel($stateId)
    {
        $state = State::findByPk($stateId);
        if (is_null($state)) {
            return $this->_r('State not found');
        }
        
        /* @var $citizenship Citizenship */
        $citizenship = Citizenship::find()->where(['stateId' => $state->id, 'userId' => $this->user->id])->one();
        if (is_null($citizenship)) {
            return $this->_r('Citizenship not found');
        }
        
        if ($citizenship->delete()) {
            return $this->_rOk();
        } else {
            return $this->_r($citizenship->getErrors());            
        }
        
    }
    
}
