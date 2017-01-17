<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\components\LinkHelper,
    app\models\politics\Region,
    yii\helpers\Html;
        
/**
 * Смена флага региона
 */
class ChangeFlagRegion extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $region->flag = $bill->dataArray['flag'];
        return $region->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        return Yii::t('app/bills', 'Change flag of region {0} to {1}', [
            LinkCreator::regionLink($region),
            Html::img($bill->dataArray['flag'], ['style' => 'height: 16px;']),
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
        }
        if (!isset($bill->dataArray['flag']) || !$bill->dataArray['flag']) {
            $bill->addError('dataArray[flag]', Yii::t('app/bills', 'Flag is required field'));
        } else if (!LinkHelper::isImageLink($bill->dataArray['flag'])) {
            $bill->addError('dataArray[flag]', Yii::t('app/bills', 'Flag must be valid link to image'));
        }
        return !!count($bill->getErrors());
        
    }

}
