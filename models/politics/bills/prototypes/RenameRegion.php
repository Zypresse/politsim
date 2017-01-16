<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProtoInterface,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\Region,
    yii\helpers\Html;
        
/**
 * Переименование региона
 */
class RenameRegion implements BillProtoInterface
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $region->name = $bill->dataArray['name'];
        $region->nameShort = $bill->dataArray['nameShort'];
        return $region->save();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        return Yii::t('app/bills', 'Rename region {0} to «{1}» ({2})', [
            LinkCreator::regionLink($region),
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
        if (!isset($bill->dataArray['regionId']) || !$bill->dataArray['regionId']) {
            $bill->addError('dataArray[regionId]', Yii::t('app', 'Region is required field'));
        } else {
            $region = Region::findByPk($bill->dataArray['regionId']);
            if (is_null($region) || $region->stateId != $bill->stateId) {
                $bill->addError('dataArray[regionId]', Yii::t('app', 'Invalid region'));
            }
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app', 'Region name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app', 'Region short name is required field'));
        }
        return !!count($bill->getErrors());
        
    }

}
