<?php

namespace app\controllers;

use Yii,
    app\components\MyController;

/**
 * 
 */
class WorkController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('index', [
            'user' => $this->user,
        ]);
    }
    
    public function actionList()
    {
        return $this->render('list', [
            'user' => $this->user,
        ]);
    }
    
}
