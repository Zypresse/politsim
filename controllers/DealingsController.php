<?php

namespace app\controllers;

use app\controllers\base\MyController;

/**
 * 
 */
final class DealingsController extends MyController
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
