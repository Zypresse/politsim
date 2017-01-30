<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    yii\widgets\ActiveForm,
    yii\web\Response,
    app\components\MyController,
    app\models\economics\Utr,
    app\models\economics\Company,
    app\models\economics\Resource,
    app\models\economics\ResourceProto,
    app\models\politics\State,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * 
 */
final class BusinessController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('index', [
            'viewer' => $this->user,
        ]);
    }
    
    public function actionShares(int $utr)
    {
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        
        $shares = Resource::find()->where([
            'masterId' => $utr,
            'protoId' => ResourceProto::SHARE,
        ])->with('company')->all();
        
        return $this->render('shares', [
            'shareholder' => $utrModel->object,
            'shares' => $shares,
            'viewer' => $this->user,
        ]);
    }
    
    public function actionCreateCompanyForm()
    {
        $model = new Company();

        if (!$this->user->tile || !$this->user->tile->region || !$this->user->tile->region->state) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $state = $this->user->tile->region->state;
        
        if (!$state->isCompaniesCreatingAllowedFor($this->user)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }

        $model->stateId = $state->id;
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('create-company-form', [
            'model' => $model,
            'user' => $this->user,
            'state' => $state,
            'article' => $state->constitution->getArticleByType(ConstitutionArticleType::BUSINESS),
        ]);
    }
    
    public function actionCreateCompany()
    { 
        $model = new Company();
        if ($model->load(Yii::$app->request->post())) {
            
            $state = State::findByPk($model->stateId);
            
            if (is_null($state)) {
                return $this->_r(Yii::t('app', 'State not found'));
            }
            
            if (!$state->isCompaniesCreatingAllowedFor($this->user)) {
                return $this->_r(Yii::t('app', 'Not allowed'));
            }
                        
            // TODO: стоимость регистрации компании
                        
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->createNew($this->user)) {
                $model->updateParams();
                $transaction->commit();
                return $this->_rOk();
            }
            $transaction->rollBack();
            return $this->_r($model->getErrors());
        }
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
        
}
