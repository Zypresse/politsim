<?php

namespace app\controllers;

use Yii,
    yii\widgets\ActiveForm,
    yii\web\Response,
    yii\filters\VerbFilter,
    app\controllers\base\MyController,
    app\models\TwitterProfile;

/**
 * 
 */
final class TwitterController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create-profile'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $profile = $this->user->profile;
        if (is_null($profile)) {
            return $this->redirect(['create-profile-form'], 301);
        }
        
        return $this->render('index', [
            'profile' => $profile,
            'user' => $this->user,
        ]);
    }
    
    public function actionCreateProfileForm()
    {
        if (!is_null($this->user->profile)) {
            return $this->redirect('/twitter');
        }
        
        $model = new TwitterProfile([
            'userId' => $this->user->id,
        ]);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('create-profile-form', [
            'user' => $this->user,
            'model' => $model,
        ]);
    }
    
    public function actionCreateProfile()
    {
        
        if (!is_null($this->user->profile)) {
            return $this->redirect('/twitter');
        }
        
        $model = new TwitterProfile([
            'userId' => $this->user->id,
        ]);
        
        if ($model->load(Yii::$app->request->post())) {
            $model->userId = $this->user->id;
            if ($model->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($model->getErrors());
            }
        } else {
            return $this->_r(Yii::t('app', 'Undefined error'));
        }
    }
    
}
