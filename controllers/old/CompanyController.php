<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    yii\web\Response,
    yii\widgets\ActiveForm,
    yii\filters\VerbFilter,
    app\controllers\base\MyController,
    app\models\economics\Utr,
    app\models\economics\Company,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionVote,
    app\models\economics\CompanyDecisionProto;

/**
 * 
 */
final class CompanyController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'new-decision'  => ['post'],
                    'decision-vote'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionView(int $id)
    {
        $company = $this->getCompany($id);
        
        return $this->render('view', [
            'company' => $company,
            'user' => $this->user,
        ]);
    }
    
    public function actionControl(int $id, int $utr)
    {
        
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        if (!$utrModel->object->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $company = $this->getCompany($id);
        
        if (!$company->isShareholder($utr)) {
            return $this->render('control-disallowed', [
                'company' => $company,
                'shareholder' => $utrModel->object,
                'user' => $this->user,
            ]);
        }
        
        return $this->render('control', [
            'company' => $company,
            'shareholder' => $utrModel->object,
            'user' => $this->user,
        ]);
    }
    
    public function actionNewDecisionListForm(int $id, int $utr)
    {
        
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        if (!$utrModel->object->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $company = $this->getCompany($id);
        
        if (!$company->isShareholder($utr)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $types = CompanyDecisionProto::findAll();
        foreach (array_keys($types) as $id) {
            $className = CompanyDecisionProto::getClassNameByType($id);
            if (!$className::isAvailable($company)) {
                unset($types[$id]);
            }
        }
        
        return $this->render('new-decision-list-form', [
            'shareholder' => $utrModel->object,
            'company' => $company,
            'types' => $types,
        ]);
    }
    
    public function actionNewDecisionForm(int $id, int $utr, int $protoId)
    {
        
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        if (!$utrModel->object->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $company = $this->getCompany($id);
        
        if (!$company->isShareholder($utr)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        if (!CompanyDecisionProto::exist($protoId)) {
            return $this->_r(Yii::t('app', 'Invalid decision type'));
        }
        $className = CompanyDecisionProto::getClassNameByType($protoId);
        if (!$className::isAvailable($company)) {
            return $this->_r(Yii::t('app', 'Decision type not available'));
        }
        
        $model = new CompanyDecision([
            'companyId' => $id,
            'initiatorId' => $utr,
            'protoId' => $protoId,
        ]);
        $proto = CompanyDecisionProto::instantiate($protoId);
        $model->dataArray = $proto->getDefaultData($model);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('new-decision-form', [
            'shareholder' => $utrModel->object,
            'company' => $company,
            'model' => $model,
        ]);
    }
    
    public function actionNewDecision()
    {
        
        $model = new CompanyDecision();
        if ($model->load(Yii::$app->request->post())) {

            $utrModel = Utr::findByPk($model->initiatorId);
            if (is_null($utrModel)) {
                throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
            }
            if (!$utrModel->object->isUserController($this->user->id)) {
                return $this->_r(Yii::t('app', 'Not allowed'));
            }

            $company = $this->getCompany($model->companyId);

            if (!$company->isShareholder($model->initiatorId)) {
                return $this->_r(Yii::t('app', 'Not allowed'));
            }

            if (!CompanyDecisionProto::exist($model->protoId)) {
                return $this->_r(Yii::t('app', 'Invalid decision type'));
            }
            $className = CompanyDecisionProto::getClassNameByType($model->protoId);
            if (!$className::isAvailable($company)) {
                return $this->_r(Yii::t('app', 'Decision type not available'));
            }
            
            if ($model->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($model->getErrors());
            }
        }
        
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionDecision(int $id, int $utr)
    {
        
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        if (!$utrModel->object->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $decision = $this->getCompanyDecision($id);
        
        if (!$decision->company->isShareholder($utr)) {
            return $this->render('control-disallowed', [
                'company' => $decision->company,
                'shareholder' => $utrModel->object,
                'user' => $this->user,
            ]);
        }
        
        return $this->render('decision', [
            'decision' => $decision,
            'user' => $this->user,
            'shareholder' => $utrModel->object,
        ]);
    }
    
    public function actionDecisionVote()
    {
        $id = (int) Yii::$app->request->post('id');
        $utr = (int) Yii::$app->request->post('utr');
        $variant = (int) Yii::$app->request->post('variant');
        
        $utrModel = Utr::findByPk($utr);
        if (is_null($utrModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'UTR not found'));
        }
        if (!$utrModel->object->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $decision = $this->getCompanyDecision($id);
        
        if (!$decision->company->isShareholder($utr)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        if ($decision->isAllreadyVoted($utr)) {
            return $this->_r(Yii::t('app', 'You allready voted for this decision'));
        }
        
        $vote = new CompanyDecisionVote([
            'decisionId' => $id,
            'shareholderId' => $utr,
            'variant' => $variant,
        ]);
        
        if ($vote->save()) {
            return $this->_rOk();
        } else {
            return $this->_r($vote->getErrors());
        }
    }
        
    private function getCompany(int $id)
    {
        $company = Company::findByPk($id);
        if (is_null($company)) {
            throw new NotFoundHttpException(Yii::t('app', 'Company not found'));
        }
        return $company;
    }
    
    private function getCompanyDecision(int $id)
    {
        $decision = CompanyDecision::findByPk($id);
        if (is_null($decision)) {
            throw new NotFoundHttpException(Yii::t('app', 'Company decision not found'));
        }
        return $decision;
    }
    
}
