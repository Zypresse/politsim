<?php

namespace app\models\politics\bills\prototypes\constitution;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\statesonly\Business as BusinessArticle;

/**
 * 
 */
final class Business extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        return $bill->state->constitution->setArticleByType(ConstitutionArticleType::BUSINESS, null, !!$bill->dataArray['value'], !!$bill->dataArray['value2']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        /* @var $article BusinessArticle */
        $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::BUSINESS);
        $article->value = isset($bill->dataArray['value']) ? !!$bill->dataArray['value'] : false;
        $article->value2 = isset($bill->dataArray['value2']) ? !!$bill->dataArray['value2'] : false;
        return Yii::t('app', 'Business:').' '.$article->name;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['value'])) {
            $bill->addError('dataArray[value]', Yii::t('app/bills', 'Allow local buisness is required field'));
        }
        if (!isset($bill->dataArray['value2'])) {
            $bill->addError('dataArray[value2]', Yii::t('app/bills', 'Allow foreign buisness is required field'));
        }
        
        return !count($bill->getErrors());
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function getDefaultData($bill)
    {
        /* @var $article BusinessArticle */
        $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::BUSINESS);
        return ['value' => !!$article->value, 'value2' => !!$article->value2];
    }

}
