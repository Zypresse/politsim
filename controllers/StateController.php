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
            return $this->render('citizenship/list', [
		'list' => $this->user->citizenships,
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
