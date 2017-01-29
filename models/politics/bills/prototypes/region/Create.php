<?php

namespace app\models\politics\bills\prototypes\region;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\politics\Region,
    app\models\Tile,
    app\components\LinkCreator,
    app\components\TileCombiner,
    yii\helpers\Html;
        
/**
 * Создание нового региона
 */
class Create extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $region = new Region([
            'name' => $bill->dataArray['name'],
            'nameShort' => $bill->dataArray['nameShort'],
            'stateId' => $bill->stateId,
        ]);
        $region->save();
        Tile::updateAll([
            'regionId' => $region->id,
        ], ['in', 'id', $bill->dataArray['tiles']]);
        $region->updateParams();
        
        $oldRegion = Region::findByPk($bill->dataArray['regionId']);
        foreach ($oldRegion->cities as $city) {
            $city->updateParams();
        }
        $oldRegion->updateParams();
        
        if ($region->biggestCity) {
            $region->link('city', $region->biggestCity);
        }
        
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        return Yii::t('app/bills', 'Seduce new region «{0}» ({1}) from region {2}', [
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
            LinkCreator::regionLink($region),
        ]);
    }

    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill) : string
    {
        $region = Region::findByPk($bill->dataArray['regionId']);
        $tilesQuery = Tile::find()->where(['in', 'id', $bill->dataArray['tiles']]);
        
        $polygonPath = Yii::$app->basePath.'/data/polygons/bills/'.$bill->id.'.json';
        
        if (file_exists($polygonPath)) {
            $polygon = file_get_contents($polygonPath);
        } else {
            $polygon = json_encode(TileCombiner::combine($tilesQuery));
            file_put_contents($polygonPath, $polygon);
        }
        
        return Yii::$app->controller->render('/bills/renderfull/region/create', [
            'bill' => $bill,
            'region' => $region,
            'polygon' => $polygon,
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill): bool
    {
        if (isset($bill->dataArray['tiles']) && is_string($bill->dataArray['tiles'])) {
            $bill->dataArray['tiles'] = explode(',', $bill->dataArray['tiles']);
        }
        
        if (!isset($bill->dataArray['regionId']) || !$bill->dataArray['regionId']) {
            $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Region is required field'));
        } else {
            $region = Region::findByPk($bill->dataArray['regionId']);
            if (is_null($region) || $region->stateId != $bill->stateId) {
                $bill->addError('dataArray[regionId]', Yii::t('app/bills', 'Invalid region'));
            } else {
                if (!isset($bill->dataArray['tiles']) || !count($bill->dataArray['tiles'])) {
                    $bill->addError('dataArray[tiles]', Yii::t('app/bills', 'Need select tiles'));
                } else {
                    $tiles = Tile::find()->where(['in', 'id', $bill->dataArray['tiles']])->all();
                    if (!count($tiles)) {
                        $bill->addError('dataArray[tiles]', Yii::t('app/bills', 'Undefined tiles'));
                    } else {
                        /* @var $tile Tile */
                        foreach ($tiles as $tile) {
                            if ($tile->regionId != $region->id) {
                                $bill->addError('dataArray[tiles]', Yii::t('app/bills', 'Invalid tiles'));
                                break;
                            }
                        }
                    }
                }
            }
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Region name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Region short name is required field'));
        }
        
        return !count($bill->getErrors());
        
    }

}
