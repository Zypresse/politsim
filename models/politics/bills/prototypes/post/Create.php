<?php

namespace app\models\politics\bills\prototypes\post;

use Yii,
    yii\helpers\Html,
    app\components\MyMathHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\ConstitutionOwnerType,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\Agency,
    app\models\politics\AgencyPost;

/**
 * 
 */
final class Create extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $post = new AgencyPost([
            'stateId' => $bill->stateId,
            'name' => $bill->dataArray['name'],
            'nameShort' => $bill->dataArray['nameShort'],
        ]);
        $post->save();
        
        $post->constitution->setArticleByType(
            ConstitutionArticleType::DESTIGNATION_TYPE, null,
            $bill->dataArray['destignationValue'],
            isset($bill->dataArray['destignationValue2']) ? $bill->dataArray['destignationValue2'] : null,
            isset($bill->dataArray['destignationValue3']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['destignationValue3']) : null
        );
        
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', 'Create new agency post «{0}» ({1})', [
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
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Agency post name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Agency post short name is required field'));
        }
        if (!isset($bill->dataArray['agencyId']) || !$bill->dataArray['agencyId']) {
            $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Agency is required field'));
        } else {
            $agency = Agency::findByPk($bill->dataArray['agencyId']);
            if (is_null($agency) || $agency->stateId != $bill->stateId) {
                $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Invalid agency'));
            }
        }
        if (!isset($bill->dataArray['destignationValue']) || !$bill->dataArray['destignationValue']) {
            $bill->addError('dataArray[destignationValue]', Yii::t('app/bills', 'Destignation type is required field'));
        } else {
            $article = new DestignationType([
                'ownerId' => $bill->postId,
                'ownerType' => ConstitutionOwnerType::POST,
                'type' => ConstitutionArticleType::DESTIGNATION_TYPE,
            ]);
            $article->value = $bill->dataArray['destignationValue'];
            $article->value2 = isset($bill->dataArray['destignationValue2']) ? $bill->dataArray['destignationValue2'] : null;
            $article->value3 = isset($bill->dataArray['destignationValue3']) ? MyMathHelper::implodeArrayToBitmask($bill->dataArray['destignationValue3']) : null;
            if (!$article->validate(['value', 'value2', 'value3'])) {
                foreach ($article->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError('dataArray[destignation'.str_replace('value', 'Value', $attr).']', $error);
                    }
                }
            }
        }
        
        return !!count($bill->getErrors());
    }

}
