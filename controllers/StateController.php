<?php

namespace app\controllers;

use Yii,
    app\components\MyController;
/**
 * Description of StateController
 *
 * @author ilya
 */
class StateController extends MyController
{
    
    public function actionIndex($id = false)
    {
        if (!$id) {
            return $this->render('citizenship/list', [
		'list' => $this->user->citizenships
	    ]);
        } else {
            return $this->render('view');
        }
    }
    
}
