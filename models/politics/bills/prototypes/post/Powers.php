<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\components\MyMathHelper,
    app\models\politics\AgencyPost,
    app\models\politics\bills\Bill,
    app\models\politics\bills\BillProto,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers as PowersArticle,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\politics\constitution\articles\postsonly\powers\Parties;

/**
 * 
 */
final class Powers extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        $post->constitution->setArticleByType(ConstitutionArticleType::POWERS, PowersArticle::BILLS, MyMathHelper::implodeArrayToBitmask($bill->dataArray['bills']));
        $post->constitution->setArticleByType(ConstitutionArticleType::POWERS, PowersArticle::PARTIES, MyMathHelper::implodeArrayToBitmask($bill->dataArray['parties']));
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return Yii::t('app/bills', 'Change powers of post {0}', [
            $post ? Html::encode($post->name) : Yii::t('app', 'Deleted agency post'),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        /* @var $article Bills */
        $articleBills = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, PowersArticle::BILLS);
        $articleBills->value = MyMathHelper::implodeArrayToBitmask($bill->dataArray['bills']);
        /* @var $article Parties */
        $articleParties = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, PowersArticle::PARTIES);
        $articleParties->value = MyMathHelper::implodeArrayToBitmask($bill->dataArray['parties']);
        return Yii::t('app/bills', 'Change powers of post {0}<br><strong>Bills powers:</strong> {1}<br><strong>Parties powers:</strong> {2}', [
            $post ? Html::encode($post->name) : Yii::t('app', 'Deleted agency post'),
            count($articleBills->getSelected()) ? implode(', ',$articleBills->getSelected()) : Yii::t('yii', '(not set)'),
            count($articleParties->getSelected()) ? implode(', ',$articleParties->getSelected()) : Yii::t('yii', '(not set)'),
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['postId']) || !$bill->dataArray['postId']) {
            $bill->addError('dataArray[postId]', Yii::t('app/bills', 'Agency post is required field'));
        } else {
            $post = AgencyPost::findByPk($bill->dataArray['postId']);
            if (is_null($post) || $post->stateId != $bill->stateId) {
                $bill->addError('dataArray[postId]', Yii::t('app/bills', 'Invalid agency post'));
            } else {
                $this->validatePowers($bill, $post);
            }
        }
        
        return !!count($bill->getErrors());
    }
    
    /**
     * 
     * @param Bill $bill
     * @param AgencyPost $post
     */
    public function validatePowers(&$bill, &$post)
    {
        if (!isset($bill->dataArray['bills'])) {
            $bill->addError('dataArray[bills]', Yii::t('app/bills', 'Bills powers is required field'));
        } else {
            /* @var $article Bills */
            $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, PowersArticle::BILLS);
            $article->value = MyMathHelper::implodeArrayToBitmask($bill->dataArray['bills']);
            if (!$article->validate(['value'])) {
                foreach ($article->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError('dataArray[bills]', $error);
                    }
                }
            }
        }
        if (!isset($bill->dataArray['parties'])) {
            $bill->addError('dataArray[parties]', Yii::t('app/bills', 'Bills powers is required field'));
        } else {
            /* @var $article Parties */
            $article = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, PowersArticle::PARTIES);
            $article->value = MyMathHelper::implodeArrayToBitmask($bill->dataArray['parties']);
            if (!$article->validate(['value'])) {
                foreach ($article->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError('dataArray[parties]', $error);
                    }
                }
            }
        }
    }

}
