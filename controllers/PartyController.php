<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\politics\Party,
    app\models\User,
    app\models\politics\PartyPost,
    app\models\politics\State,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\statesonly\Parties,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Parties as PowersParties,
    app\models\politics\AgencyPost,
    yii\web\Response,
    yii\widgets\ActiveForm,
    yii\filters\VerbFilter;

/**
 * 
 */
class PartyController extends MyController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'confirm'  => ['post'],
                    'revoke'  => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex($id)
    {
        $party = Party::findByPk($id);
        
        if (is_null($party)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        return $this->render('view', [
            'party' => $party,
            'user' => $this->user
        ]);
    }
    
    public function actionMembers($id)
    {
        $party = Party::findByPk($id);
        
        if (is_null($party)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        return $this->render('members', [
            'party' => $party,
            'members' => $party->getMembers()->with('partyPosts')->orderBy(['fame' => SORT_DESC, 'trust' => SORT_DESC, 'success' => SORT_DESC])->all(),
            'user' => $this->user
        ]);
    }
        
    public function actionProgram($id)
    {
        $party = Party::findByPk($id);
        
        if (is_null($party)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        return $this->render('program', [
            'party' => $party,
            'user' => $this->user
        ]);
    }
    
    public function actionCreate()
    {
                        
        $model = new Party();
        if ($model->load(Yii::$app->request->post())) {
            
            $state = State::findByPk($model->stateId);
            
            if (is_null($state)) {
                return $this->_r(Yii::t('app', 'State not found'));
            }
            
            if (!$state->isPartiesCreatingAllowed) {
                return $this->_r(Yii::t('app', 'Not allowed'));
            }
            
            if ($this->user->getParties()->where(['stateId' => $model->stateId])->exists()) {
                return $this->_r(Yii::t('app', 'You allready have party membership in this state'));
            }
            
            $article = $state->constitution->getArticleByType(ConstitutionArticleType::PARTIES);
            if ($article->value3) {
                // TODO: стоимость регистрации партия
            }
            
            $autoConfirm = !($article->value == Parties::NEED_CONFIRM);
            
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->createNew($this->user, $autoConfirm)) {
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
            if (!$state->isPartiesCreatingAllowed) {
                return $this->_r(Yii::t('app', 'Not allowed'));
            }
            
            $model->stateId = $state->id;
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('create-form', [
            'model' => $model,
            'user' => $this->user,
            'article' => $state->constitution->getArticleByType(ConstitutionArticleType::PARTIES),
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
            return $this->_r(Yii::t('app', "Party not found"));
        }
        
        $userPost = $party->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r(Yii::t('app', "Access denied"));
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
                return $this->_r(Yii::t('app', "Party not found"));
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
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        $userPost = $model->party->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r(Yii::t('app', "Access denied"));
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
            return $this->_r(Yii::t('app', "Party post not found"));
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
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        $userPost = $model->party->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r(Yii::t('app', "Access denied"));
        }
        
        $userId = $model->userId;
        $modelCopy = clone $model;
        if ($model->delete()) {
            if ($userId) {
                $this->notificator->deletedPartyPost($userId, $modelCopy);
            }
            return $this->_rOk();
        }
        
        return $this->_r(Yii::t('app', 'Undefined error'));
    }
    
    public function actionSetSuccessor($postId, $userId)
    {
        
        $model = PartyPost::findByPk($postId);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        if (!$model->userId || $model->userId != $this->user->id || $model->appointmentType != PartyPost::APPOINTMENT_TYPE_INHERITANCE) {
            return $this->_r(Yii::t('app', "Access denied"));
        }
        
        $user = User::findByPk($userId);
        
        if (is_null($user)) {
            return $this->_r(Yii::t('app', "User not found"));
        }
        
        if (!$user->isHaveMembership($model->partyId)) {
            return $this->_r(Yii::t('app', "User have not membership in this party"));
        }
        
        $model->successorId = $user->id;
        if ($model->save()) {
            $this->notificator->settedAsSuccessorToPartyPost($userId, $model);
            return $this->_rOk();
        } else {
            return $this->_r($model->getErrors());
        }
    }
    
    public function actionSetSuccessorForm($postId)
    {
        
        $model = PartyPost::findByPk($postId);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        $candidats = $model->party->getMembers()->where(['<>', 'id', $this->user->id])->orderBy(['fame' => SORT_DESC, 'trust' => SORT_DESC, 'success' => SORT_DESC])->all();
        
        return $this->render('set-successor-form', [
            'model' => $model,
            'candidats' => $candidats,
            'user' => $this->user
        ]);
    }
    
    public function actionSetPost($postId, $userId)
    {
        
        $model = PartyPost::findByPk($postId);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        $setterPost = $model->party->getPostByUserId($this->user->id);
        
        if ($model->appointmentType != PartyPost::APPOINTMENT_TYPE_LEADER || !$setterPost || !($setterPost->powers & PartyPost::POWER_EDIT_POSTS)) {
            return $this->_r(Yii::t('app', "Access denied"));
        }
        
        $user = User::findByPk($userId);
        
        if (is_null($user)) {
            return $this->_r(Yii::t('app', "User not found"));
        }
        
        if (!$user->isHaveMembership($model->partyId)) {
            return $this->_r(Yii::t('app', "User have not membership in this party"));
        }
        
        $model->userId = $user->id;
        if ($model->save()) {
            $this->notificator->settedToPartyPost($userId, $model);
            return $this->_rOk();
        } else {
            return $this->_r($model->getErrors());
        }
    }
    
    public function actionSetPostForm($postId)
    {
        
        $model = PartyPost::findByPk($postId);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        $candidats = $model->party->getMembers()->where(['<>', 'id', $this->user->id])->orderBy(['fame' => SORT_DESC, 'trust' => SORT_DESC, 'success' => SORT_DESC])->all();
        
        return $this->render('set-post-form', [
            'model' => $model,
            'candidats' => $candidats,
            'user' => $this->user
        ]);
    }
    
    public function actionDropPost($id)
    {
        
        $model = PartyPost::findByPk($id);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party post not found"));
        }
        
        $setterPost = $model->party->getPostByUserId($this->user->id);
        
        if ($model->appointmentType == PartyPost::APPOINTMENT_TYPE_LEADER) {
            if (!$setterPost || !($setterPost->powers & PartyPost::POWER_EDIT_POSTS)) {
                return $this->_r(Yii::t('app', "Access denied"));
            }
        } elseif ($model->id != $setterPost->id) {
            return $this->_r(Yii::t('app', "Access denied"));
        }
        
        $dropedUser = $model->user;
        $settedUser = $model->successor;
        
        $model->userId = $model->successorId;
        if ($model->save()) {
            if ($dropedUser) {
                $this->notificator->droppedFromPartyPost($dropedUser->id, $model);
            }
            if ($settedUser) {
                $this->notificator->droppedFromPartyPost($settedUser->id, $model);
            }
            return $this->_rOk();
        } else {
            return $this->_r($model->getErrors());
        }
    }
    
    public function actionEdit()
    {
        
        $id = Yii::$app->request->post('Party')['id'];
        
        $model = Party::findByPk($id);
        
        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        
        $userPost = $model->getPostByUserId($this->user->id);
        if (is_null($userPost) || !($userPost->powers & PartyPost::POWER_CHANGE_FIELDS)) {
            return $this->_r(Yii::t('app', "Access denied"));
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
    
    public function actionEditForm($id = false)
    {
        
        if (!$id) {
            $id = Yii::$app->request->post('Party')['id'];
        }
        
        $model = Party::findByPk($id);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('edit-form', [
            'model' => $model,
            'user' => $this->user
        ]);
    }
    
    public function actionEditTextForm($id = false)
    {
        
        if (!$id) {
            $id = Yii::$app->request->post('Party')['id'];
        }
        
        $model = Party::findByPk($id);

        if (is_null($model)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        return $this->render('edit-text-form', [
            'model' => $model,
            'user' => $this->user
        ]);
    }
    
    public function actionConfirm()
    {
        $postId = (int) Yii::$app->request->post('postId');
        $partyId = (int) Yii::$app->request->post('partyId');
        
        if (!$postId || !$partyId) {
            return $this->_r(Yii::t('app', 'Invalid params'));
        }
        
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        
        $post = AgencyPost::findByPk($postId);
        if (is_null($post)) {
            return $this->_r(Yii::t('app', "Agency post not found"));
        }
        
        if ($party->stateId != $post->stateId) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        /* @var $article PowersParties */
        $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::PARTIES);
        if (!$article->isSelected(PowersParties::ACCEPT)) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        if ($party->isConfirmed) {
            return $this->_r(Yii::t('app', "Party already registered"));
        }
        
        $party->confirm();
        return $this->_rOk();
        
    }
    
    public function actionRevoke()
    {
        $postId = (int) Yii::$app->request->post('postId');
        $partyId = (int) Yii::$app->request->post('partyId');
        
        if (!$postId || !$partyId) {
            return $this->_r(Yii::t('app', 'Invalid params'));
        }
        
        $party = Party::findByPk($partyId);
        if (is_null($party)) {
            return $this->_r(Yii::t('app', "Party not found"));
        }
        
        $post = AgencyPost::findByPk($postId);
        if (is_null($post)) {
            return $this->_r(Yii::t('app', "Agency post not found"));
        }
        
        if ($party->stateId != $post->stateId) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        /* @var $article PowersParties */
        $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::PARTIES);
        if (!$article->isSelected(PowersParties::REVOKE)) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        if ($party->isDeleted) {
            return $this->_r(Yii::t('app', "Party already deleted"));
        }
        
        $party->delete();
        return $this->_rOk();
        
    }
    
}
