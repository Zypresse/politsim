<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProtoInterface,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\components\LinkHelper,
    app\models\politics\Region,
    yii\helpers\Html;
        
/**
 * Смена флага региона
 */
class ChangeAnthemRegion implements BillProtoInterface
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $region->anthem = $bill->dataArray['anthem'];
        return $region->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        return Yii::t('app/bills', 'Change anthem of region {0} to {1}', [
            LinkCreator::regionLink($region),
            Html::a($bill->dataArray['anthem'], $bill->dataArray['anthem']),
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
        if (!isset($bill->dataArray['anthem']) || !$bill->dataArray['anthem']) {
            $bill->addError('dataArray[anthem]', Yii::t('app/bills', 'Anthem is required field'));
        } else if (!LinkHelper::isSoundCloudLink($bill->dataArray['anthem'])) {
            $bill->addError('dataArray[anthem]', Yii::t('app/bills', 'Anthem must be valid SoundCloud link'));
        }
        return !!count($bill->getErrors());
        
    }

}
