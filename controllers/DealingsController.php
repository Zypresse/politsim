<?php

namespace app\controllers;

use Yii,
    app\components\MyController;

/**
 * 
 */
class DealingsController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('list', [
            'initiated' => $this->user->dealingsInitiated,
            'received' => $this->user->dealingsReceived,
            'user' => $this->user,
        ]);
    }
    
}
