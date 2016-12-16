<?php

namespace app\controllers;

use app\components\MyController,
    app\models\politics\Party,
    app\models\politics\State;

/**
 * Рейтинги
 *
 * @author dev
 */
class ChartController extends MyController
{
    
    public function actionStates()
    {
        $list = State::find()->orderBy(['population' => SORT_DESC])->all();
        return $this->render('states', [
            'list' => $list
        ]);
    }
    
    public function actionParties()
    {
        $list = Party::find()->orderBy(['fame' => SORT_DESC])->with('state')->all();
        return $this->render('parties', [
            'list' => $list
        ]);
    }
    
}
