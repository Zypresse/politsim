<?php

namespace app\models\politics\bills\prototypes\region;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\Region,
    yii\helpers\Html;
        
/**
 * Переименование региона
 */
class Rename extends BillProto
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
            $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Region is required field'));
        } else {
            $region = Region::findByPk($bill->dataArray['regionId']);
            if (is_null($region) || $region->stateId != $bill->stateId) {
                $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Invalid region'));
            }
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Region name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Region short name is required field'));
        }
        
        if (!count($bill->getErrors()) && isset($region)) {
            $region->name = $bill->dataArray['name'];
            $region->nameShort = $bill->dataArray['nameShort'];
            if (!$region->validate()) {
                foreach ($region->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $bill->addError("dataArray[{$attr}]", $error);
                    }
                }
            }
        }
        
        return !count($bill->getErrors());
        
    }

}
