<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\models\politics\AgencyPost,
    app\components\MyController;

/**
 * 
 */
final class PostController extends MyController
{
    
    public function actionView(int $id)
    {
        $post = $this->getPost($id);
        return $this->render('view', [
            'post' => $post,
            'user' => $this->user,
        ]);
    }
    
    public function actionConstitution(int $postId, $types)
    {
        $post = $this->getPost($postId);
        $this->result = [];
        foreach (explode(',', $types) as $type) {
            if (strpos($type,':') > -1) {
                list($type, $subType) = explode(':', $type);
            } else {
                $subType = null;
            }
            $article = $post->constitution->getArticleByTypeOrEmptyModel($type, $subType);
            $this->result[] = $article->getPublicAttributes();
        }
        return $this->_r();
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
            throw new NotFoundHttpException(Yii::t('app', 'Agency post not found'));
        }
        return $post;
    }
    
}
