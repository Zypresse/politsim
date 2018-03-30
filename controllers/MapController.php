<?php

namespace app\controllers;

use Yii;
use app\controllers\base\AppController;
use app\models\government\State;

/**
 * Description of MapController
 *
 * @author ilya
 */
class MapController extends AppController
{
    
    public function actionPolitical()
    {
        return $this->render('political', [
            'states' => State::findActive()->with('polygon')->all(),
        ]);
    }
    
}
