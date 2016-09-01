<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\User,
    app\models\State;

/**
 * Рейтинги
 *
 * @author dev
 */
class ChartController extends MyController {
    
    public function actionStates()
    {
        $list = State::find()->orderBy(['population' => SORT_DESC])->all();
        return $this->render('states', [
            'list' => $list
        ]);
    }
    
}
