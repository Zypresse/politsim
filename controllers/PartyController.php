<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\Party,
    app\models\State,
    yii\web\Response,
    yii\widgets\ActiveForm;

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

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('create-form', [
            'model' => $model,
            'user' => $this->user
        ]);
    }
    
}
