<?php

namespace app\models\politics\bills\prototypes\constitution;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * Смена партийной политики государства
 */
class Parties extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        return $bill->state->constitution->setArticleByType(ConstitutionArticleType::PARTIES, null, $bill->dataArray['value'], $bill->dataArray['value2'], $bill->dataArray['value3']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Parties */
        $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::PARTIES);
        $article->value = $bill->dataArray['value'];
        $article->value2 = isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null;
        $article->value3 = isset($bill->dataArray['value3']) ? $bill->dataArray['value3'] : null;
        return Yii::t('app/bills', 'Change parties politic to {0}', [$article->name]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill): string
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Parties */
        $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::PARTIES);
        $article->value = $bill->dataArray['value'];
        $article->value2 = isset($bill->dataArray['value2']) ? $bill->dataArray['value2'] : null;
        $article->value3 = isset($bill->dataArray['value3']) ? $bill->dataArray['value3'] : null;
        
        return $article->getFullName();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
            $bill->addError('dataArray[value]', Yii::t('app/bills', 'Parties politic is required field'));
        } elseif (!isset($bill->dataArray['value2'])) {
            $bill->addError('dataArray[value2]', Yii::t('app/bills', 'Ruling party is required field'));
        } elseif (!isset($bill->dataArray['value3'])) {
            $bill->addError('dataArray[value3]', Yii::t('app/bills', 'Parties registration cost is required field'));
        } else {
            /* @var $article \app\models\politics\constitution\articles\statesonly\Parties */
            $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::PARTIES);
            $article->value = $bill->dataArray['value'];
            $article->value2 = $bill->dataArray['value2'];
            $article->value3 = $bill->dataArray['value3'];
            if (!$article->validate(['value', 'value2', 'value3'])) {
                foreach ($article->getErrors() as $attr => $errors) {
                    if ($attr == 'value3') {
                        $bill->addError('dataArray[value3]', Yii::t('app/bills', 'Parties registration cost is required field'));
                    } else {
                        foreach ($errors as $error) {
                            $bill->addError('dataArray['.$attr.']', $error);
                        }
                    }
                }
            }
        }
        return !count($bill->getErrors());
    }
    
    public function getDefaultData($bill)
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Parties */
        $article = $bill->state->constitution->getArticleByType(ConstitutionArticleType::PARTIES);
        return ['value' => $article->value, 'value2' => $article->value2, 'value3' => $article->value3];
    }

}
