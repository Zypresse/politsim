<?php

namespace app\models\politics\bills\prototypes\region;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\Region,
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
        $region = Region::findByPk($bill->dataArray['regionId']);
        return $region->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $bill->dataArray['value']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $post = AgencyPost::findByPk($bill->dataArray['value']);
        return Yii::t('app/bills', 'Set agency post «{0}» as leader of region {1}', [
            Html::encode($post->name),
            LinkCreator::regionLink($region),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['regionId']) || !$bill->dataArray['regionId']) {
            $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Region is required field'));
        } else {
            $region = Region::findByPk($bill->dataArray['regionId']);
            if (is_null($region) || $region->stateId != $bill->stateId) {
                $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Invalid region'));
            }
            if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
                $bill->addError('dataArray[value]', Yii::t('app/bills', 'Region leader is required field'));
            } else {
                $article = $region->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::LEADER_POST);
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
