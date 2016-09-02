<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\State;

/**
 * Description of MapController
 *
 * @author ilya
 */
class MapController extends MyController
{
    
    public function actionIndex()
    {
        $states = State::find()->where(['dateDeleted' => null])->all();
        return $this->render('index', [
            'states' => $states
        ]);
    }
    
}
