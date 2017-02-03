<?php

namespace app\controllers;

use Yii,
    app\controllers\base\MyController,
    app\models\economics\License,
    app\models\User,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Licenses,
    app\models\politics\AgencyPost,
    yii\filters\VerbFilter;

/**
 * 
 */
final class LicenseController extends MyController
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
    
    public function actionConfirm()
    {
        $postId = (int) Yii::$app->request->post('postId');
        $licenseId = (int) Yii::$app->request->post('licenseId');
        
        if (!$postId || !$licenseId) {
            return $this->_r(Yii::t('app', 'Invalid params'));
        }
        
        $license = License::findByPk($licenseId);
        if (is_null($license)) {
            return $this->_r(Yii::t('app', "License not found"));
        }
        
        $post = AgencyPost::findByPk($postId);
        if (is_null($post)) {
            return $this->_r(Yii::t('app', "Agency post not found"));
        }
        
        if ($license->stateId != $post->stateId) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        /* @var $article Licenses */
        $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::LICENSES);
        if (!$article->isSelected(Licenses::ACCEPT)) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        if ($license->dateGranted) {
            return $this->_r(Yii::t('app', "License already granted"));
        }
        
        $license->dateGranted = time();
        $license->dateExpired = time()+$license->state->getLicenseGrantedTime($license->protoId);
        $license->save();
        
        foreach ($license->company->shares as $share) {
            if (!$share->master->getUserControllerId() || !User::find()->where(['id' => $share->master->getUserControllerId()])->exists()) {
                continue;
            }
            Yii::$app->notificator->licenseGranted($share->master->getUserControllerId(), $license);
        }
        return $this->_rOk();
        
    }
    
    public function actionRevoke()
    {
        $postId = (int) Yii::$app->request->post('postId');
        $licenseId = (int) Yii::$app->request->post('licenseId');
        
        if (!$postId || !$licenseId) {
            return $this->_r(Yii::t('app', 'Invalid params'));
        }
        
        $license = License::findByPk($licenseId);
        if (is_null($license)) {
            return $this->_r(Yii::t('app', "License not found"));
        }
        
        $post = AgencyPost::findByPk($postId);
        if (is_null($post)) {
            return $this->_r(Yii::t('app', "Agency post not found"));
        }
        
        if ($license->stateId != $post->stateId) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        /* @var $article Licenses */
        $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::LICENSES);
        if (!$article->isSelected(Licenses::ACCEPT)) {
            return $this->_r(Yii::t('app', "Not allowed"));
        }
        
        if ($license->dateExpired < time() || is_null($license->dateGranted)) {
            return $this->_r(Yii::t('app', "License can not be revoked"));
        }
        
        foreach ($license->company->shares as $share) {
            if (!$share->master->getUserControllerId() || !User::find()->where(['id' => $share->master->getUserControllerId()])->exists()) {
                continue;
            }
            Yii::$app->notificator->licenseRevoked($share->master->getUserControllerId(), $license);
        }
        
        $license->delete();
        return $this->_rOk();
        
    }
}
