<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\State;

/**
 * 
 */
class StateController extends MyController
{
    
    public function actionIndex($id = false)
    {
        if (!$id) {
            return $this->render('/citizenship/list', [
		'approved' => $this->user->getApprovedCitizenships()->with('state')->all(),
		'requested' => $this->user->getRequestedCitizenships()->with('state')->all(),
                'user' => $this->user
	    ]);
        } else {
            $state = State::findByPk($id);
            if (is_null($state)) {
                return $this->_r("State not found");
            }
            return $this->render('view', [
                'state' => $state,
                'user' => $this->user
            ]);
        }
    }
    
}
