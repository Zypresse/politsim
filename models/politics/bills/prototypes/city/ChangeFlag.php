<?php

namespace app\models\politics\bills\prototypes\city;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\components\LinkHelper,
    app\models\politics\City,
    yii\helpers\Html;

/**
 * Смена флага города
 */
class ChangeFlag extends BillProto
{

    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        $city->flag = $bill->dataArray['flag'];
        return $city->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        return Yii::t('app/bills', 'Change flag of city {0} to {1}', [
            LinkCreator::cityLink($city),
            Html::img($bill->dataArray['name'], ['style' => 'height: 16px;']),
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
        if (!isset($bill->dataArray['flag']) || !$bill->dataArray['flag']) {
            $bill->addError('dataArray[flag]', Yii::t('app/bills', 'Flag is required field'));
        } else if (!LinkHelper::isImageLink($bill->dataArray['flag'])) {
            $bill->addError('dataArray[flag]', Yii::t('app/bills', 'Flag must be valid link to image'));
        }
        return !count($bill->getErrors());
    }

}
