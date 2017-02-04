<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\AgencyPost,
    yii\helpers\Html;
        
/**
 * Переименование поста
 */
class Rename extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        $post->name = $bill->dataArray['name'];
        $post->nameShort = $bill->dataArray['nameShort'];
        return $post->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return Yii::t('app/bills', 'Rename agency post {0} to «{1}» ({2})', [
            $post ? Html::encode($post->name) : Yii::t('app', 'Deleted agency post'),
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
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
            }
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Agency post name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Agency post short name is required field'));
        }
        
        if (!count($bill->getErrors()) && isset($post)) {
            $post->name = $bill->dataArray['name'];
            $post->nameShort = $bill->dataArray['nameShort'];
            if (!$post->validate()) {
                foreach ($post->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError("dataArray[{$attr}]", $error);
                    }
                }
            }
        }
        
        return !count($bill->getErrors());
        
    }

}
