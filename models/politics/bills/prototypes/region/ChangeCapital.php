<?php

namespace app\models\politics\bills\prototypes\region;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\City,
    app\models\politics\Region,
    yii\helpers\Html;

/**
 * Перенести столицу региона
 */
class ChangeCapital extends BillProto
{

    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $region->cityId = $bill->dataArray['cityId'];
        return $region->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $city = City::findByPk($bill->dataArray['cityId']);
        return Yii::t('app/bills', 'Change capital of region {0} to city {1}', [
            LinkCreator::regionLink($region),
            LinkCreator::cityLink($city),
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
            } else {
                if (!isset($bill->dataArray['cityId']) || !$bill->dataArray['cityId']) {
                    $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'City is required field'));
                } else {
                    $city = City::findByPk($bill->dataArray['cityId']);
                    if (is_null($city) || $city->regionId != $region->id) {
                        $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'Invalid city'));
                    } elseif ($city->id == $region->cityId) {
                        $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'City allready is capital'));
                    }
                }
            }
        }
        
        return !count($bill->getErrors());
    }
    
}
