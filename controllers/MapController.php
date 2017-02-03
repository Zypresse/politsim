<?php

namespace app\controllers;

use Yii,
    app\controllers\base\MyController,
    app\models\politics\State;

/**
 * Description of MapController
 *
 * @author ilya
 */
final class MapController extends MyController
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
    
    public function actionDemography($mode = '2d')
    {
        $list = json_decode(file_get_contents(Yii::$app->basePath.'/data/polygons/popdestiny.json'));
        
        return $this->render($mode == '2d' ? 'demography' : 'demography3d', [
            'list' => $list,
        ]);
    }
    
}
