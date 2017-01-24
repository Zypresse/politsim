<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\components\MyMathHelper,
    app\models\politics\AgencyPost,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType;

/**
 * 
 */
final class Destignation extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']);
        return $post->constitution->setArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE, null, $bill->dataArray['value'], $bill->dataArray['value2'], MyMathHelper::implodeArrayToBitmask($bill->dataArray['value3']));
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['postId']); // TODO кем выбирается и настройки выборов
        return Yii::t('app/bills', 'Change agency post «{0}» destignation type to {1}', [
            Html::encode($post->name),
            DestignationType::getNameStatic($bill->dataArray['value']),
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
                if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
                    $bill->addError('dataArray[value]', Yii::t('app/bills', 'Destignation type is required field'));
                } elseif (!isset($bill->dataArray['value2'])) {
                    $bill->addError('dataArray[value2]', Yii::t('app/bills', 'Destignator is required field'));
                } elseif (!isset($bill->dataArray['value3'])) {
                    $bill->addError('dataArray[value3]', Yii::t('app/bills', 'Elections rules is required field'));
                } else {
                    /* @var $article DestignationType */
                    $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
                    $article->value = $bill->dataArray['value'];
                    $article->value2 = $bill->dataArray['value2'];
                    $article->value3 = MyMathHelper::implodeArrayToBitmask($bill->dataArray['value3']);
                    if (!$article->validate(['value', 'value2', 'value3'])) {
                        foreach ($article->getErrors() as $attr => $errors) {
                            foreach ($errors as $error) {
                                $bill->addError('dataArray['.$attr.']', $error);
                            }
                        }
                    }
                }
            }
        }
        
        return !!count($bill->getErrors());
    }

}
