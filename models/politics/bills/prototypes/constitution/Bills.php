<?php

namespace app\models\politics\bills\prototypes\constitution;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * Смена настроек создания законопроектов
 */
class Bills extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        return $bill->state->constitution->setArticleByType(ConstitutionArticleType::BILLS, null, $bill->dataArray['value'], $bill->dataArray['value2']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Bills */
        $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::BILLS);
        $article->value = $bill->dataArray['value'];
        $article->value2 = isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null;
        return Yii::t('app/bills', 'Change bills creating rules to {0}', [$article->name]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Bills */
        $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::BILLS);
        $article->value = $bill->dataArray['value'];
        $article->value2 = isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null;        
        return $article->getFullName();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
            $bill->addError('dataArray[value]', Yii::t('app/bills', 'Voting time is required field'));
        } elseif (!isset($bill->dataArray['value2']) || !$bill->dataArray['value2']) {
            $bill->addError('dataArray[value2]', Yii::t('app/bills', 'Count of active bills by post is required field'));
        } else {
            /* @var $article \app\models\politics\constitution\articles\statesonly\Bills */
            $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::BILLS);
            $article->value = $bill->dataArray['value'];
            $article->value2 = $bill->dataArray['value2'];
            if (!$article->validate(['value', 'value2'])) {
                foreach ($article->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError('dataArray['.$attr.']', $error);
                    }
                }
            }
        }
        return !count($bill->getErrors());
    }
    
    public function getDefaultData($bill)
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Bills */
        $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::BILLS);
        return ['value' => $article->value, 'value2' => $article->value2];
    }

}
