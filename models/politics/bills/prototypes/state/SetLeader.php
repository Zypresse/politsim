<?php

namespace app\models\politics\bills\prototypes\state;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\AgencyPost,
    app\models\politics\constitution\ConstitutionArticleType,
    yii\helpers\Html;

/**
 * 
 */
final class SetLeader extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        return $bill->state->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $bill->dataArray['value']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $post = AgencyPost::findByPk($bill->dataArray['value']);
        return Yii::t('app/bills', 'Set agency post «{0}» as leader of our state', [
            Html::encode($post->name),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
            $bill->addError('dataArray[value]', Yii::t('app/bills', 'State leader is required field'));
        } else {
            $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::LEADER_POST);
            $article->value = $bill->dataArray['value'];
            if (!$article->validate(['value'])) {
                foreach ($article->getErrors('value') as $error) {
                    $bill->addError('dataArray[value]', $error);
                }
            }
        }
        return !count($bill->getErrors());
    }
    
    public function getDefaultData($bill)
    {
        $article = $bill->state->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::LEADER_POST);
        return [
            'value' => $article->value,
        ];
    }

}
