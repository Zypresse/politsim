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
                    
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->createNew($this->user)) {
                $transaction->commit();
                return $this->_rOk();
            }
            $transaction->rollBack();
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
    
    public function actionCreatePost()
    {
        $partyId = Yii::$app->request->post('PartyPost')['partyId'];
        if (!$partyId) {
            return $this->_r(Yii::t('app', 'Invalid party ID'));
        }
        
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r("Party not found");
        }
        
        $userPost = $party->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r("Access denied");
        }
        
        $model = new PartyPost();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($model->getErrors());
            }
        }
        
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionCreatePostForm($partyId = false)
    {
        
        $model = new PartyPost();
        
        if ($partyId) {
            $party = Party::findByPk($partyId);

            if (is_null($party)) {
                return $this->_r("Party not found");
            }
            $model->partyId = $party->id;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('create-post-form', [
            'model' => $model,
            'party' => $party,
            'user' => $this->user
        ]);
    }
    
    public function actionEditPost()
    {
        $id = Yii::$app->request->post('PartyPost')['id'];
        
        $model = PartyPost::findByPk($id);
        
        if (is_null($model)) {
            return $this->_r("Party post not found");
        }
        
        $userPost = $model->party->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r("Access denied");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($model->getErrors());
            }
        }
        
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionEditPostForm($postId = false)
    {
        
        if (!$postId) {
            $postId = Yii::$app->request->post('PartyPost')['id'];
        }
        
        $model = PartyPost::findByPk($postId);

        if (is_null($model)) {
            return $this->_r("Party post not found");
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('edit-post-form', [
            'model' => $model,
            'user' => $this->user
        ]);
    }
    
    public function actionDeletePost($id)
    {
        
        $model = PartyPost::findByPk($id);
        
        if (is_null($model)) {
            return $this->_r("Party post not found");
        }
        
        $userPost = $model->party->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r("Access denied");
        }
        
        if ($model->delete()) {
            return $this->_rOk();
        }
        
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
}
