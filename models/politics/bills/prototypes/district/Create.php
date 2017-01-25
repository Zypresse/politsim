<?php

namespace app\models\politics\bills\prototypes\district;

use Yii,
    app\models\politics\bills\BillProto,
    app\models\politics\bills\Bill,
    app\models\Tile,
    app\models\politics\elections\ElectoralDistrict,
    app\components\TileCombiner,
    yii\helpers\Html;
        
/**
 * Создание нового избирательного округа
 */
class Create extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $district = new ElectoralDistrict([
            'name' => $bill->dataArray['name'],
            'nameShort' => $bill->dataArray['nameShort'],
            'stateId' => $bill->stateId,
        ]);
        $district->save();
        Tile::updateAll([
            'electoralDistrictId' => $district->id,
        ], ['in', 'id', $bill->dataArray['tiles']]);
        $district->updateParams();
        
        $oldDistrict = ElectoralDistrict::findByPk($bill->dataArray['districtId']);
        $oldDistrict->updateParams();
        
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $district = ElectoralDistrict::findByPk($bill->dataArray['districtId']);
        return Yii::t('app/bills', 'Seduce new electoral district «{0}» ({1}) from electoral district «{2}»', [
            Html::encode($bill->dataArray['name']),
            Html::encode($bill->dataArray['nameShort']),
            Html::encode($district->name),
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill) : string
    {
        $district = ElectoralDistrict::findByPk($bill->dataArray['districtId']);
        $tilesQuery = Tile::find()->where(['in', 'id', $bill->dataArray['tiles']]);
        
        $polygonPath = Yii::$app->basePath.'/data/polygons/bills/'.$bill->id.'.json';
        
        if (file_exists($polygonPath)) {
            $polygon = file_get_contents($polygonPath);
        } else {
            $polygon = json_encode(TileCombiner::combine($tilesQuery));
            file_put_contents($polygonPath, $polygon);
        }
        
        return Yii::$app->controller->render('/bills/renderfull/district/create', [
            'bill' => $bill,
            'district' => $district,
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
        
        if (!isset($bill->dataArray['districtId']) || !$bill->dataArray['districtId']) {
            $bill->addError('dataArray[districtId]', Yii::t('app/bills', 'Electoral district is required field'));
        } else {
            $district = ElectoralDistrict::findByPk($bill->dataArray['districtId']);
            if (is_null($district) || $district->stateId != $bill->stateId) {
                $bill->addError('dataArray[districtId]', Yii::t('app/bills', 'Invalid electoral district'));
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
                            if ($tile->electoralDistrictId != $district->id) {
                                $bill->addError('dataArray[tiles]', Yii::t('app/bills', 'Invalid tiles'));
                                break;
                            }
                        }
                    }
                }
            }
        }
        if (!isset($bill->dataArray['name']) || !$bill->dataArray['name']) {
            $bill->addError('dataArray[name]', Yii::t('app/bills', 'Electoral district name is required field'));
        }
        if (!isset($bill->dataArray['nameShort']) || !$bill->dataArray['nameShort']) {
            $bill->addError('dataArray[nameShort]', Yii::t('app/bills', 'Electoral district short name is required field'));
        }
        
        return !!count($bill->getErrors());
        
    }

}
