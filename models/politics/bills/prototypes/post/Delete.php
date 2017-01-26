<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\AgencyPost;

/**
 * 
 */
final class Delete extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return $post->delete();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return Yii::t('app/bills', 'Delete agency post «{0}»', [
            $post ? Html::encode($post->name) : Yii::t('app', 'Deleted agency post'),
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
            } elseif (!$post->canBeDeleted()) {
                $bill->addError('dataArray[postId]', Yii::t('app/bills', 'This agency post can not be deleted'));
            }
        }
        
        return !!count($bill->getErrors());
    }

}
