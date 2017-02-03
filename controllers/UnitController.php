<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    yii\web\Response,
    yii\filters\VerbFilter,
    yii\widgets\ActiveForm,
    app\components\MyController,
    app\models\economics\Utr,
    app\models\economics\UtrType,
    app\models\economics\units\Unit,
    app\models\economics\units\UnitProto,
    app\models\economics\units\Vacancy;

/**
 * 
 */
final class UnitController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'vacancy-delete'  => ['post'],
                    'vacancy-create'  => ['post'],
                    'vacancy-edit'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex(int $id)
    {
        $building = $this->loadBuilding($id);
        return $this->render('view', [
            'building' => $building,
            'user' => $this->user,
        ]);
    }
    
    public function actionFutureInfo(int $protoId, int $size)
    {
        $proto = UnitProto::instantiate($protoId);
        return $this->render('future-info', [
            'proto' => $proto,
            'size' => $size,
        ]);
    }
    
    public function actionVacancies(int $id)
    {
        $building = $this->loadBuilding($id);
        if (!$building->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        return $this->render('vacancies', [
            'building' => $building,
            'user' => $this->user,
        ]);
    }
    
    public function actionVacancyCreateForm(int $id)
    {
        $building = $this->loadBuilding($id);
        if (!$building->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $model = new Vacancy([
            'objectId' => $building->getUtr(),
        ]);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('vacancies-create', [
            'building' => $building,
            'user' => $this->user,
            'model' => $model,
        ]);
    }
    
    public function actionVacancyCreate()
    {
        $model = new Vacancy();
        if ($model->load(Yii::$app->request->post())) {
            
            $building = Utr::findByPk($model->objectId)->object;

            if ($building->getUtrType() !== UtrType::UNIT || !$building->isUserController($this->user->id)) {
                return $this->_r(Yii::t('app', 'Not allowed'));
            }
            
            if ($model->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($model->getErrors());
            }
        }
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionVacancyDelete()
    {
        $building = $this->loadBuilding(Yii::$app->request->post('id'));
        if (!$building->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $vacancy = Vacancy::findByPk(Yii::$app->request->post('vacancyId'));
        if (is_null($vacancy) || (int)$vacancy->objectId !== (int)$building->getUtr()) {
            throw new NotFoundHttpException(Yii::t('app', 'Vacancy not found'));
        }
        
        $vacancy->delete();
        return $this->_rOk();
    }
    
    public function actionVacancyEditForm(int $id, int $vacancyId)
    {
        $building = $this->loadBuilding($id);
        if (!$building->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $vacancy = Vacancy::findByPk($vacancyId);
        if (is_null($vacancy) || (int)$vacancy->objectId !== (int)$building->getUtr()) {
            throw new NotFoundHttpException(Yii::t('app', 'Vacancy not found'));
        }
                
        if (Yii::$app->request->isAjax && $vacancy->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($vacancy);
        }
        
        return $this->render('vacancies-edit', [
            'building' => $building,
            'user' => $this->user,
            'model' => $vacancy,
        ]);
    }
    
    public function actionVacancyEdit(int $id, int $vacancyId)
    {
        $building = $this->loadBuilding($id);
        if ($building->getUtrType() !== UtrType::UNIT || !$building->isUserController($this->user->id)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $vacancy = Vacancy::findByPk($vacancyId);
        if (is_null($vacancy) || (int)$vacancy->objectId !== (int)$building->getUtr()) {
            throw new NotFoundHttpException(Yii::t('app', 'Vacancy not found'));
        }
        
        if ($vacancy->load(Yii::$app->request->post()) && $vacancy->save()) {
            return $this->_rOk();
        } else {
            return $this->_r($vacancy->getErrors());
        }
    }
    
    /**
     * 
     * @param integer $id
     * @return Building
     * @throws NotFoundHttpException
     */
    private function loadBuilding(int $id)
    {
        $building = Unit::findByPk($id);
        if (is_null($building)) {
            throw new NotFoundHttpException(Yii::t('app', 'Firm not found'));
        }
        return $building;
    }
    
}
