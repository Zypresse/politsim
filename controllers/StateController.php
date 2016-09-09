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
    
    public function actionIndex($id)
    {
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
