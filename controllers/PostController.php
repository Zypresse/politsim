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
    
    public function actionConstitution(int $postId, $types)
    {
        $post = $this->getPost($postId);
        $this->result = [];
        foreach (explode(',', $types) as $type) {
            $article = $post->constitution->getArticleByType($type);
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
