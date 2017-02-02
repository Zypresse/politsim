<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\models\User,
    app\models\politics\AgencyPost,
    app\models\politics\bills\Bill,
    app\models\politics\bills\BillProto,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    yii\widgets\ActiveForm,
    yii\web\Response,
    yii\filters\VerbFilter,
    app\components\MyController;

/**
 * 
 */
class WorkController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'destignate-to-post'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render($this->user->getPosts()->count() ? 'index' : 'have-no-work', [
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
        
        if ($protoId) {
            $proto = BillProto::instantiate($protoId);
            $model->dataArray = $proto->getDefaultData($model);
        }
                
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        $types = BillProto::findAll();
        foreach ($types as $id => $name) {
            $className = BillProto::getClassNameByType($id);
            if (!$className::isAvailable($post->state)) {
                unset($types[$id]);
            }
        }
        
        
        return $protoId ? $this->render('new-bill-form', [
            'model' => $model,
            'post' => $post,
        ]) : $this->render('new-bill-list-form', [
            'post' => $post,
            'types' => $types,
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
            
            $article = $post->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::BILLS);
            $countBillsByPostAllowed = (int)$article->value2;
            if ($countBillsByPostAllowed > 0) {
                $countBillsByPost = (int)$post->state->getBillsActive()
                                            ->andWhere(['postId' => $post->id])
                                            ->count();
                if ($countBillsByPost >= $countBillsByPostAllowed) {
                    return $this->_r(Yii::t('app', 'You can not make more {0} bills in same time', [$countBillsByPostAllowed]));
                }
            }
            
            if ($model->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($model->getErrors());
            }
        }
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionDestignateToPostForm(int $postId, int $targetPostId)
    {
        $post = $this->loadPost($postId);
        $targetPost = $this->loadPost($targetPostId);
        
        $article = $targetPost->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        if ((int)$article->value != DestignationType::BY_OTHER_POST || (int)$article->value2 != (int)$post->id) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        return $this->render('destignate-to-post-form', [
            'post' => $post,
            'targetPost' => $targetPost,
        ]);
    }
    
    public function actionDestignateToPost()
    {
        $postId = (int) Yii::$app->request->post('postId');
        $targetPostId = (int) Yii::$app->request->post('targetPostId');
        $userId = (int) Yii::$app->request->post('userId');
        
        $post = $this->loadPost($postId);
        $targetPost = $this->loadPost($targetPostId);
        $user = $this->loadUser($userId);
        
        $article = $targetPost->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        if ((int)$article->value != DestignationType::BY_OTHER_POST || (int)$article->value2 != (int)$post->id) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        if (!$user->isHaveCitizenship($targetPost->stateId)) {
            return $this->_r(Yii::t('app', 'User have not citizenship of this state'));
        }
        
        if ($user->getPostsByState($targetPost->stateId)->exists()) {
            $article2 = $targetPost->state->constitution->getArticleByType(ConstitutionArticleType::MULTIPOST, null);
            if (!$article2->value) {
                return $this->_r(Yii::t('app', 'User allready have agency post'));
            }
        }
        
        if ($targetPost->destignate($user)) {
            return $this->_rOk();
        } else {
            return $this->_r($targetPost->getErrors());
        }
        
    }
    
    public function actionRemoveFromPostForm(int $postId, int $targetPostId)
    {
        $post = $this->loadPost($postId);
        $targetPost = $this->loadPost($targetPostId);
        
        $article = $targetPost->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        if ((int)$article->value != DestignationType::BY_OTHER_POST || (int)$article->value2 != (int)$post->id) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        return $this->render('remove-from-post-form', [
            'post' => $post,
            'targetPost' => $targetPost,
        ]);
    }
    
    public function actionRemoveFromPost()
    {
        $postId = (int) Yii::$app->request->post('postId');
        $targetPostId = (int) Yii::$app->request->post('targetPostId');
        
        $post = $this->loadPost($postId);
        $targetPost = $this->loadPost($targetPostId);
        
        $article = $targetPost->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        if ((int)$article->value != DestignationType::BY_OTHER_POST || (int)$article->value2 != (int)$post->id) {
            return $this->_r(Yii::t('app', 'Not allowed'));
        }
        
        if ($targetPost->removeUser()) {
            return $this->_rOk();
        } else {
            return $this->_r($targetPost->getErrors());
        }
        
    }
    
    private function loadPost(int $id)
    {
        $post = AgencyPost::findByPk($id);
        if (is_null($post)) {
            throw new NotFoundHttpException(Yii::t('app','Goverment post not found'));
        }
        return $post;
    }
    
    private function loadUser(int $id)
    {
        $user = User::findByPk($id);
        if (is_null($user)) {
            throw new NotFoundHttpException(Yii::t('app','User not found'));
        }
        return $user;
    }
    
}
