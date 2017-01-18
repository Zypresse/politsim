<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\City,
    yii\helpers\Html;

/**
 * Перенести столицу государства
 */
class ChangeCapitalState extends BillProto
{

    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $bill->state->cityId = $bill->dataArray['cityId'];
        return $bill->state->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $city = City::findByPk($bill->dataArray['cityId']);
        return Yii::t('app/bills', 'Change our state capital to city {0}', [
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
            if (is_null($city) || $city->region->stateId != $bill->stateId) {
                $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'Invalid city'));
            } elseif ($city->id == $bill->state->cityId) {
                $bill->addError('dataArray[cityId]', Yii::t('app/bills', 'City allready is capital'));
            }
        }
        return !!count($bill->getErrors());
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function getDefaultData($bill)
    {
        return [
            'cityId' => $bill->state->cityId,
        ];
    }

}
