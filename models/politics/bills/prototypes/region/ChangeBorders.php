<?php

namespace app\models\politics\bills\prototypes\region;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\Region,
    app\models\Tile,
    app\components\LinkCreator,
    app\components\TileCombiner;
        
/**
 * Изменение границы регионов
 */
class ChangeBorders extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region1 = Region::findByPk($bill->dataArray['region1Id']);
        $region2 = Region::findByPk($bill->dataArray['region2Id']);
        Tile::updateAll(['regionId' => $region1->id], ['in', 'id', $bill->dataArray['tiles1']]);
        Tile::updateAll(['regionId' => $region2->id], ['in', 'id', $bill->dataArray['tiles2']]);
        foreach ($region1->cities as $city) {
            $allTilesIn2 = true;
            foreach ($city->tiles as $tile) {
                if ($tile->regionId != $region1->id) {
                    $allTilesIn2 = false;
                    break;
                }
            }
            if ($allTilesIn2) {
                $city->link('region', $region2);
            }
        }
        foreach ($region2->cities as $city) {
            $allTilesIn1 = true;
            foreach ($city->tiles as $tile) {
                if ($tile->regionId != $region2->id) {
                    $allTilesIn1 = false;
                    break;
                }
            }
            if ($allTilesIn1) {
                $city->link('region', $region1);
            }
        }
        
        $region1->updateParams();
        $region2->updateParams();
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
        return Yii::t('app/bills', 'Change border between regions {0} and {1}', [
            LinkCreator::regionLink($region1),
            LinkCreator::regionLink($region2),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill) : string
    {
        $region1 = Region::findByPk($bill->dataArray['region1Id']);
        $region2 = Region::findByPk($bill->dataArray['region2Id']);
        $tiles1query = Tile::find()->where(['in', 'id', $bill->dataArray['tiles1']]);
        $tiles2query = Tile::find()->where(['in', 'id', $bill->dataArray['tiles2']]);
        
        $polygon1path = Yii::$app->basePath.'/data/polygons/bills/'.$bill->id.'-1.json';
        $polygon2path = Yii::$app->basePath.'/data/polygons/bills/'.$bill->id.'-2.json';
        
        if (file_exists($polygon1path)) {
            $polygon1 = file_get_contents($polygon1path);
        } else {
            $polygon1 = json_encode(TileCombiner::combine($tiles1query));
            file_put_contents($polygon1path, $polygon1);
        }
        if (file_exists($polygon2path)) {
            $polygon2 = file_get_contents($polygon2path);
        } else {
            $polygon2 = json_encode(TileCombiner::combine($tiles2query));
            file_put_contents($polygon2path, $polygon2);
        }
        
        return Yii::$app->controller->render('/bills/renderfull/region/change-borders', [
            'region1' => $region1,
            'region2' => $region2,
            'polygon1' => $polygon1,
            'polygon2' => $polygon2,
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (isset($bill->dataArray['tiles1']) && is_string($bill->dataArray['tiles1'])) {
            $bill->dataArray['tiles1'] = explode(',', $bill->dataArray['tiles1']);
        }
        if (isset($bill->dataArray['tiles2']) && is_string($bill->dataArray['tiles2'])) {
            $bill->dataArray['tiles2'] = explode(',', $bill->dataArray['tiles2']);
        }
        
        if (!isset($bill->dataArray['region1Id']) || !$bill->dataArray['region1Id']) {
            $bill->addError('dataArray[region1Id]', Yii::t('app/bills', 'First region is required field'));
        } else {
            $region1 = Region::findByPk($bill->dataArray['region1Id']);
            if (is_null($region1) || $region1->stateId != $bill->stateId) {
                $bill->addError('dataArray[region1Id]', Yii::t('app/bills', 'Invalid region'));
            }
        }
        if (!isset($bill->dataArray['region2Id']) || !$bill->dataArray['region2Id']) {
            $bill->addError('dataArray[region2Id]', Yii::t('app/bills', 'First region is required field'));
        } else {
            $region2 = Region::findByPk($bill->dataArray['region2Id']);
            if (is_null($region2) || $region2->stateId != $bill->stateId) {
                $bill->addError('dataArray[region2Id]', Yii::t('app/bills', 'Invalid region'));
            }
        }
        
        if (isset($region1) && isset($region2)) {
            if (!isset($bill->dataArray['tiles1']) || !count($bill->dataArray['tiles1'])) {
                $bill->addError('dataArray[tiles1]', Yii::t('app/bills', 'Need select tiles for first region'));
            } else {
                $tiles1 = Tile::find()->where(['in', 'id', $bill->dataArray['tiles1']])->all();
                if (!count($tiles1)) {
                    $bill->addError('dataArray[tiles1]', Yii::t('app/bills', 'Undefined tiles'));
                } else {
                    /* @var $tile Tile */
                    foreach ($tiles1 as $tile) {
                        if ($tile->regionId != $region1->id && $tile->regionId != $region2->id) {
                            $bill->addError('dataArray[tiles1]', Yii::t('app/bills', 'Invalid tiles'));
                            break;
                        }
                    }
                }
            }
            if (!isset($bill->dataArray['tiles2']) || !count($bill->dataArray['tiles2'])) {
                $bill->addError('dataArray[tiles2]', Yii::t('app/bills', 'Need select tiles for second region'));
            } else {
                $tiles2 = Tile::find()->where(['in', 'id', $bill->dataArray['tiles2']])->all();
                if (!count($tiles2)) {
                    $bill->addError('dataArray[tiles2]', Yii::t('app/bills', 'Undefined tiles'));
                } else {
                    /* @var $tile Tile */
                    foreach ($tiles2 as $tile) {
                        if ($tile->regionId != $region1->id && $tile->regionId != $region2->id) {
                            $bill->addError('dataArray[tiles2]', Yii::t('app/bills', 'Invalid tiles'));
                            break;
                        }
                    }
                }
            }
        }
        
        return !count($bill->getErrors());
        
    }

}
