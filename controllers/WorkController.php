<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\models\politics\AgencyPost,
    app\models\politics\bills\Bill,
    app\models\politics\bills\BillProto,
    yii\widgets\ActiveForm,
    yii\web\Response,
    app\components\MyController;

/**
 * 
 */
class WorkController extends MyController
{
    
    public function actionIndex()
    {
        return $this->render('index', [
            'user' => $this->user,
        ]);
    }
    
    public function actionList()
    {
        return $this->render('list', [
            'user' => $this->user,
        ]);
    }
    
    public function actionNewBillForm(int $postId, int $protoId = null)
    {
        $post = $this->loadPost($postId);
        
        if (!$post->constitution->isCanCreateNewBill()) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $model = new Bill();
        $model->postId = $postId;
        $model->stateId = $post->stateId;
        $model->protoId = $protoId;
        $model->userId = $this->user->id;
        
        $views = [
            BillProto::RENAME_STATE => 'rename-state',
        ];
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render($protoId ? 'bills/'.$views[$protoId] : 'new-bill-form', [
            'model' => $model,
            'post' => $post,
            'types' => BillProto::findAll()
        ]);
    }
    
    public function actionNewBill()
    {
        $model = new Bill();
        if ($model->load(Yii::$app->request->post())) {
            
            $post = $this->loadPost($model->postId);

            if (!$post->constitution->isCanCreateNewBill()) {
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
    
    private function loadPost(int $id)
    {
        $post = AgencyPost::findByPk($id);
        if (is_null($post)) {
            throw new NotFoundHttpException('Goverment post not found');
        }
        return $post;
    }
    
}
