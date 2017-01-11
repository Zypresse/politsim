<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\models\politics\AgencyPost,
    app\models\politics\bills\Bill,
    app\models\politics\bills\BillProto,
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
    
    public function actionNewBillForm(int $postId)
    {
        $post = $this->loadPost($postId);
        
        if (!$post->constitution->isCanCreateNewBill()) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        $model = new Bill();
        $model->postId = $postId;
        $model->userId = $this->user->id;
        
        return $this->render('new-bill-form', [
            'model' => $model,
            'post' => $post,
            'types' => BillProto::findAll()
        ]);
    }
    
    public function actionNewBill()
    {
        
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
