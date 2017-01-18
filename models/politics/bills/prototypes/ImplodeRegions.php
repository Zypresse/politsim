<?php

namespace app\models\politics\bills\prototypes;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\components\LinkCreator,
    app\models\politics\Region,
    app\models\politics\City,
    app\models\Tile;
        
/**
 * Включить второй рег в состав первого
 */
class ImplodeRegions extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region1 = Region::findByPk($bill->dataArray['region1Id']);
        $region2 = Region::findByPk($bill->dataArray['region2Id']);
        
        Tile::updateAll(['regionId' => $region1->id], ['regionId' => $region2->id]);
        City::updateAll(['regionId' => $region1->id], ['regionId' => $region2->id]);
        $region2->implodedTo = $region1->id;
        $region2->delete();
        $region1->updateParams();
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region1 = Region::findByPk($bill->dataArray['region1Id']);
        $region2 = Region::findByPk($bill->dataArray['region2Id']);
        return Yii::t('app/bills', 'Include region {0} to region {1}', [
            LinkCreator::regionLink($region2),
            LinkCreator::regionLink($region1),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (!isset($bill->dataArray['region1Id']) || !$bill->dataArray['region1Id']) {
            $bill->addError('dataArray[region1Id]', Yii::t('app/bills', 'Main region is required field'));
        } else {
            $region1 = Region::findByPk($bill->dataArray['region1Id']);
            if (is_null($region1) || $region1->stateId != $bill->stateId) {
                $bill->addError('dataArray[region1Id]', Yii::t('app/bills', 'Invalid region'));
            }
        }
        if (!isset($bill->dataArray['region2Id']) || !$bill->dataArray['region2Id']) {
            $bill->addError('dataArray[region2Id]', Yii::t('app/bills', 'Children region is required field'));
        } else {
            $region2 = Region::findByPk($bill->dataArray['region2Id']);
            if (is_null($region2) || $region2->stateId != $bill->stateId) {
                $bill->addError('dataArray[region2Id]', Yii::t('app/bills', 'Invalid region'));
            }
        }
        
        if (isset($bill->dataArray['region1Id']) && isset($bill->dataArray['region2Id'])) {
            if ((int)$bill->dataArray['region1Id'] === (int)$bill->dataArray['region2Id']) {
                $bill->addError('dataArray[region2Id]', Yii::t('app/bills', 'Select some other region'));
            }
        }
        return !!count($bill->getErrors());
        
    }

}
