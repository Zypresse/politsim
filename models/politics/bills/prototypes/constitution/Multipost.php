<?php

namespace app\models\politics\bills\prototypes\constitution;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\constitution\ConstitutionArticleType;

/**
 * 
 */
class Multipost extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        return $bill->state->constitution->setArticleByType(ConstitutionArticleType::MULTIPOST, null, !!$bill->dataArray['value']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        return Yii::t('app/bills', $bill->dataArray['value'] ? 'Allow more than one agency post to user' : 'Disallow more than one agency post to user');
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['value'])) {
            $bill->addError('dataArray[value]', Yii::t('app/bills', 'Multipost politic is required field'));
        }
        
        return !count($bill->getErrors());
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function getDefaultData($bill)
    {
        /* @var $article \app\models\politics\constitution\articles\statesonly\Multipost */
        $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::MULTIPOST);
        return ['value' => !!$article->value];
    }

}
