<?php

namespace app\controllers;

use app\components\MyController,
    app\models\politics\State,
    app\models\politics\Citizenship;

/**
 * 
 */
class CitizenshipController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('list', [
            'approved' => $this->user->getApprovedCitizenships()->with('state')->all(),
            'requested' => $this->user->getRequestedCitizenships()->with('state')->all(),
            'user' => $this->user
        ]);
    }

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
        
        if ($citizenship->approve()) {
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
        
        if ($citizenship->fireSelf()) {
            return $this->_rOk();
        } else {
            return $this->_r($citizenship->getErrors());            
        }
        
    }
    
}
