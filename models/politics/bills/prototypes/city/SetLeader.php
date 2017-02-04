<?php

namespace app\models\politics\bills\prototypes\city;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\City,
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
        $city = City::findByPk($bill->dataArray['cityId']);
        return $city->constitution->setArticleByType(ConstitutionArticleType::LEADER_POST, null, $bill->dataArray['value']);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        $post = AgencyPost::findByPk($bill->dataArray['value']);
        return Yii::t('app/bills', 'Set agency post «{0}» as leader of city {1}', [
            Html::encode($post->name),
            LinkCreator::cityLink($city),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        
        if (!isset($bill->dataArray['cityId']) || !$bill->dataArray['cityId']) {
            $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'City is required field'));
        } else {
            $city = City::findByPk($bill->dataArray['cityId']);
            if (is_null($city) || is_null($city->region) || $city->region->stateId != $bill->stateId) {
                $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'Invalid city'));
            }
            if (!isset($bill->dataArray['value']) || !$bill->dataArray['value']) {
                $bill->addError('dataArray[value]', Yii::t('app/bills', 'City leader is required field'));
            } else {
                $article = $city->constitution->getArticleByTypeOrEmptyModel(ConstitutionArticleType::LEADER_POST);
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
