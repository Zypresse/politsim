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
    
    public function actionIndex($mode = '2d')
    {
        $states = State::find()->where(['dateDeleted' => null])->all();
        return $this->render($mode == '2d' ? 'index' : 'index3d', [
            'states' => $states
        ]);
    }
    
    public function actionState($id, $mode = '2d')
    {
        $state = State::findByPk($id);
        if (is_null($state)) {
            return $this->_r(Yii::t('app', 'State not found'));
        }
        
        return $this->render($mode == '2d' ? 'state': 'state3d', [
            'state' => $state
        ]);
    }
    
}
