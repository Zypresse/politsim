<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\components\LinkCreator,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType;

/**
 * 
 */
final class Fire extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return $post->removeUser();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return Yii::t('app/bills', 'Fire user {1} from agency post «{0}»', [
            $post ? Html::encode($post->name) : Yii::t('app', 'Deleted agency post'),
            $post && $post->user ? LinkCreator::userLink($post->user) : Yii::t('app', 'Undefined user'),
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
                /* @var $article DestignationType */
                $article = $post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
                if ((int)$article->value === DestignationType::BY_PRECURSOR) {
                    $bill->addError('dataArray[postId]', Yii::t('app/bills', 'Not allowed fire from agency posts, destignated by precursor'));
                }
                
                if (!$post->user) {
                    $bill->addError('dataArray[postId]', Yii::t('app/bills', 'Agency posts have not user'));
                }
            }
        }
        
        return !!count($bill->getErrors());
    }

}
