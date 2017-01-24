<?php

namespace app\models\politics\bills\prototypes\city;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\City,
    yii\helpers\Html;

/**
 * Переименование города
 */
class Rename extends BillProto
{

    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        $city->name = $bill->dataArray['name'];
        $city->nameShort = $bill->dataArray['nameShort'];
        return $city->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        return Yii::t('app/bills', 'Rename city {0} to «{1}» ({2})', [
            LinkCreator::cityLink($city),
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
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
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'City name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'City short name is required field'));
        }
        return !!count($bill->getErrors());
    }

}
