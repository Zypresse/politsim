<?php

namespace app\models\politics\bills\prototypes\agency;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\Agency,
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
        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        return $agency->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $bill->dataArray['value']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $agency = Agency::findByPk($bill->dataArray['agencyId']);
        $post = AgencyPost::findByPk($bill->dataArray['value']);
        return Yii::t('app/bills', 'Set agency post «{0}» as leader of agency {1}', [
            Html::encode($post->name),
            LinkCreator::agencyLink($agency),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['agencyId']) || !$bill->dataArray['agencyId']) {
            $bill->addError('dataArray[agencyId]', Yii::t('app/bills', 'Region is required field'));
        } else {
            $agency = Agency::findByPk($bill->dataArray['agencyId']);
            if (is_null($agency) || $agency->stateId != $bill->stateId) {
                $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Invalid region'));
            }
            if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
                $bill->addError('dataArray[value]', Yii::t('app/bills', 'Agency leader is required field'));
            } else {
                $article = $agency->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::LEADER_POST);
                $article->value = $bill->dataArray['value'];
                if (!$article->validate(['value'])) {
                    foreach ($article->getErrors('value') as $error) {
                        $bill->addError('dataArray[value]', $error);
                    }
                }
            }
        }
        return !count($bill->getErrors());
        
    }

}
