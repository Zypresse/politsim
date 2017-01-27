<?php

namespace app\models\politics\bills\prototypes\constitution;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\statesonly\Buisness as BuisnessArticle;

/**
 * 
 */
final class Buisness extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        return $bill->state->constitution->setArticleByType(ConstitutionArticleType::BUISNESS, null, !!$bill->dataArray['value']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return $bill->dataArray['value'] ? Yii::t('app', 'Allow buisness') : Yii::t('app', 'Disallow buisness');
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['value'])) {
            $bill->addError('dataArray[value]', Yii::t('app/bills', 'Allow buisness is required field'));
        }
        
        return !count($bill->getErrors());
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function getDefaultData($bill)
    {
        /* @var $article BuisnessArticle */
        $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::BUISNESS);
        return ['value' => !!$article->value];
    }

}
