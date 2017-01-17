<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProtoInterface,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\components\LinkHelper,
    app\models\politics\City,
    yii\helpers\Html;

/**
 * Смена флага города
 */
class ChangeAnthemCity implements BillProtoInterface
{

    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        $city->anthem = $bill->dataArray['anthem'];
        return $city->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        return Yii::t('app/bills', 'Change anthem of city {0} to {1}', [
            LinkCreator::cityLink($city),
            Html::a($bill->dataArray['anthem'], $bill->dataArray['anthem']),
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
            if (is_null($city) || $city->region->stateId != $bill->stateId) {
                $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'Invalid city'));
            }
        }
        if (!isset($bill->dataArray['anthem']) || !$bill->dataArray['anthem']) {
            $bill->addError('dataArray[anthem]', Yii::t('app/bills', 'Anthem is required field'));
        } else if (!LinkHelper::isSoundCloudLink($bill->dataArray['anthem'])) {
            $bill->addError('dataArray[anthem]', Yii::t('app/bills', 'Anthem must be valid SoundCloud link'));
        }
        return !!count($bill->getErrors());
    }

}
