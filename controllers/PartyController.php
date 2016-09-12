<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\Party,
    app\models\State;

/**
 * 
 */
class PartyController extends MyController
{
    
    public function actionIndex()
    {
        if (count($this->user->parties)) {
            return "aaa";
        } else {
            return $this->render('none', [
                'user' => $this->user
            ]);
        }
    }
    
    public function actionCreate()
    {
                
        $model = new Party();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->_rOk();
            }
        }
        
        return $this->_r($model->getErrors());
    }
    
    public function actionCreateForm($stateId)
    {
        
        $state = State::findByPk($stateId);
        if (is_null($state)) {
            return $this->_r(Yii::t('app', 'State not found'));
        }
        
        $model = new Party();
        $model->stateId = $state->id;
        
        return $this->render('create-form', [
            'model' => $model,
            'user' => $this->user
        ]);
    }
    
}
