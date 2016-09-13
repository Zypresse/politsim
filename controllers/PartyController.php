<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\Party,
    app\models\Membership,
    app\models\PartyPost,
    app\models\State,
    yii\web\Response,
    yii\widgets\ActiveForm;

/**
 * 
 */
class PartyController extends MyController
{
    
    public function actionIndex($id)
    {
        $party = Party::findByPk($id);
        
        if (is_null($party)) {
            return $this->_r("Party not found");
        }
        return $this->render('view', [
            'party' => $party,
            'user' => $this->user
        ]);
    }
    
    public function actionCreate()
    {
                        
        $model = new Party();
        if ($model->load(Yii::$app->request->post())) {
            
            $isUserHavePartyAllready = !!$this->user->getParties()->where(['stateId' => $model->stateId])->count();
            if ($isUserHavePartyAllready) {
                return $this->_r(Yii::t('app', 'You allready have party membership in this state'));
            }
                    
            if ($model->createNew($this->user)) {
                return $this->_rOk();
            }
        
            return $this->_r($model->getErrors());
        }
        
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionCreateForm($stateId = false)
    {
        $model = new Party();
        
        if ($stateId) {        
            $state = State::findByPk($stateId);
            if (is_null($state)) {
                return $this->_r(Yii::t('app', 'State not found'));
            }
            $model->stateId = $state->id;
        }        

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
