<?php

namespace app\controllers;

use app\controllers\base\AppController;
use app\models\government\State;

/**
 * Рейтинги
 *
 * @author ilya
 */
final class RatingController extends AppController
{
    
    public function actionStates()
    {
        $list = State::findActive()
                ->orderBy(['population' => SORT_DESC])
                ->all();
        return $this->render('states', [
            'list' => $list
        ]);
    }
    
}
