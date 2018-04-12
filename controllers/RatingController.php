<?php

namespace app\controllers;

use app\controllers\base\AppController;
use app\models\government\State;
use app\models\politics\Organization;

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
    
    public function actionOrganizations()
    {
        $list = Organization::findActive()
                ->orderBy(['name' => SORT_ASC])
                ->all();
        return $this->render('organizations', [
            'list' => $list
        ]);
    }
    
}
