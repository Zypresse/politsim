<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    yii\filters\VerbFilter,
    app\components\MyController,
    app\models\politics\bills\Bill,
    app\models\politics\bills\BillVote,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\Bills;

/**
 * 
 */
final class BillsController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'view'  => ['get'],
                    'vote'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionView(int $id)
    {
        $bill = $this->getBill($id);
        return $this->render('view', [
            'bill' => $bill,
            'user' => $this->user,
        ]);
    }
    
    public function actionVote()
    {
        
        $billId = (int) Yii::$app->request->post('billId');
        $postId = (int) Yii::$app->request->post('postId');
        $variant = (int) Yii::$app->request->post('variant');
        
        if ($variant <= 0) {
            return $this->_r(Yii::t('app', 'Invalid variant'));
        }
        
        $bill = $this->getBill($billId);
        $post = $this->getPost($postId);
        
        if ($bill->stateId != $post->stateId) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        /* @var $article Bills */
        $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS);
        if (!$article->isSelected(Bills::VOTE)) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        if ($bill->isAllreadyVoted($postId)) {
            return $this->_r(Yii::t('app', 'You allready voted for this bill'));
        }
        
        $vote = new BillVote([
            'billId' => $billId,
            'postId' => $postId,
            'variant' => $variant,
        ]);
        
        if ($vote->save()) {
            return $this->_rOk();
        } else {
            return $this->_r($vote->getErrors());
        }
        
    }
    
    /**
     * 
     * @param integer $id
     * @return Bill
     * @throws NotFoundHttpException
     */
    private function getBill(int $id)
    {
        $bill = Bill::findByPk($id);
        if (is_null($bill)) {
            throw new NotFoundHttpException(Yii::t('app', 'Bill not found'));
        }
        return $bill;
    }
    
    /**
     * 
     * @param integer $id
     * @return AgencyPost
     * @throws NotFoundHttpException
     */
    private function getPost(int $id)
    {
        $post = AgencyPost::findByPk($id);
        if (is_null($post)) {
            throw new NotFoundHttpException(Yii::t('app', 'Post not found'));
        }
        return $post;
    }
    
}
