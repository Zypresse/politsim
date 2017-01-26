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
 * Изменение границы избирательных округов
 */
class ChangeBorders extends BillProto
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill): bool
    {
        $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
        $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
        
        Tile::updateAll(['electoralDistrictId' => $district1->id], ['in', 'id', $bill->dataArray['tiles1']]);
        Tile::updateAll(['electoralDistrictId' => $district2->id], ['in', 'id', $bill->dataArray['tiles2']]);
                
        $district1->updateParams();
        $district2->updateParams();
        return true;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function render($bill): string
    {
        $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
        $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
        return Yii::t('app/bills', 'Change border between electoral districts «{0}» and «{1}»', [
            Html::encode($district1->name),
            Html::encode($district2->name),
        ]);
    }
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill) : string
    {
        $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
        $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
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
        
        return Yii::$app->controller->render('/bills/renderfull/district/change-borders', [
            'district1' => $district1,
            'district2' => $district2,
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
        
        if (!isset($bill->dataArray['district1Id']) || !$bill->dataArray['district1Id']) {
            $bill->addError('dataArray[district1Id]', Yii::t('app/bills', 'First electoral district is required field'));
        } else {
            $district1 = ElectoralDistrict::findByPk($bill->dataArray['district1Id']);
            if (is_null($district1) || $district1->stateId != $bill->stateId) {
                $bill->addError('dataArray[district1Id]', Yii::t('app/bills', 'Invalid electoral district'));
            }
        }
        if (!isset($bill->dataArray['district2Id']) || !$bill->dataArray['district2Id']) {
            $bill->addError('dataArray[district2Id]', Yii::t('app/bills', 'First region is required field'));
        } else {
            $district2 = ElectoralDistrict::findByPk($bill->dataArray['district2Id']);
            if (is_null($district2) || $district2->stateId != $bill->stateId) {
                $bill->addError('dataArray[region2Id]', Yii::t('app/bills', 'Invalid electoral district'));
            }
        }
        
        if (isset($district1) && isset($district2)) {
            if (!isset($bill->dataArray['tiles1']) || !count($bill->dataArray['tiles1'])) {
                $bill->addError('dataArray[tiles1]', Yii::t('app/bills', 'Need select tiles for first region'));
            } else {
                $tiles1 = Tile::find()->where(['in', 'id', $bill->dataArray['tiles1']])->all();
                if (!count($tiles1)) {
                    $bill->addError('dataArray[tiles1]', Yii::t('app/bills', 'Undefined tiles'));
                } else {
                    /* @var $tile Tile */
                    foreach ($tiles1 as $tile) {
                        if ($tile->electoralDistrictId != $district1->id && $tile->electoralDistrictId != $district2->id) {
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
                        if ($tile->electoralDistrictId != $district1->id && $tile->electoralDistrictId != $district2->id) {
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
