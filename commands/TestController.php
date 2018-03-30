<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\government\State;
use app\models\map\Region;
use app\models\map\Tile;
use app\models\map\Polygon;
use app\components\RegionCombiner;

/**
 * Description of TestController
 *
 * @author ilya
 */
class TestController extends Controller
{
    
    const RIGHT_KOREA = 702;
    const WRONG_KOREA = 703;
    
    const SEUL = 1000;
    const PHENJAN = 990;
    
    const JP_REGIONS = [483,484,485,486,487,488,489,490];
    
    const TOKYO = 237;
    
    public function actionCreateKorea()
    {
        
        Region::updateAll(['stateId' => null], ['is not', 'stateId', null]);
        State::deleteAll();
        $north = Region::findOne(self::RIGHT_KOREA);
        $south = Region::findOne(self::WRONG_KOREA);
        
        $rightKorea = new State([
            'name' => 'Корейская Народно-Демократическая Республика',
            'nameShort' => 'КНДР',
            'cityId' => self::PHENJAN,
            'mapColor' => '990000',
            'population' => $north->population,
        ]);
        $rightKorea->save();
        $wrongKorea = new State([
            'name' => 'Республика Корея',
            'nameShort' => 'РК',
            'cityId' => self::SEUL,
            'mapColor' => '000099',
            'population' => $south->population,
        ]);
        $wrongKorea->save();
        
        $north->stateId = $rightKorea->id;
        $south->stateId = $wrongKorea->id;
        $north->save();
        $south->save();
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $rightKorea->id,
            'data' => RegionCombiner::combine($rightKorea->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $wrongKorea->id,
            'data' => RegionCombiner::combine($wrongKorea->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        
        
        $japan = new State([
            'name' => 'Япония',
            'nameShort' => 'Япония',
            'cityId' => self::TOKYO,
            'mapColor' => 'ee5599',
            'population' => Region::find()->where(['id' => self::JP_REGIONS])->sum('population'),
        ]);
        $japan->save();
        
        Region::updateAll(['stateId' => $japan->id], ['id' => self::JP_REGIONS]);
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $japan->id,
            'data' => RegionCombiner::combine($japan->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        
        $china = new State([
            'name' => 'Китайская Народная Республика',
            'nameShort' => 'КНР',
            'cityId' => 238,
            'mapColor' => 'aa6600',
            'population' => Region::find()->where(['BETWEEN', 'id', 491, 524])->andWhere(['not in', 'id', [520, 519]])->sum('population'),
        ]);
        $china->save();
        
        Region::updateAll(['stateId' => $china->id], ['and', ['BETWEEN', 'id', 491, 524], ['not in', 'id', [520, 519]]]);
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $china->id,
            'data' => RegionCombiner::combine($china->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        
        $mongol = new State([
            'name' => 'Монголия',
            'nameShort' => 'Монголия',
            'cityId' => 173,
            'mapColor' => '66aa00',
            'population' => Region::find()->where(['id' => 345])->sum('population'),
        ]);
        $mongol->save();
        
        Region::updateAll(['stateId' => $mongol->id], ['id' => 345]);
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $mongol->id,
            'data' => RegionCombiner::combine($mongol->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
                
        $wrongChina = new State([
            'name' => 'Китайская Республика',
            'nameShort' => 'Тайвань',
            'cityId' => 1256,
            'mapColor' => '00ff99',
            'population' => Region::find()->where(['BETWEEN', 'id', 525, 526])->sum('population'),
        ]);
        $wrongChina->save();
        
        Region::updateAll(['stateId' => $wrongChina->id], ['BETWEEN', 'id', 525, 526]);
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $wrongChina->id,
            'data' => RegionCombiner::combine($wrongChina->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        
        
        $ru = Region::find()->select('id')->orWhere(['like', 'nameShort', 'RU-'])->orWhere(['id' => 304])->column();
         
        $russia = new State([
            'name' => 'Российская Федерация',
            'nameShort' => 'Россия',
            'cityId' => 11,
            'mapColor' => 'ff0000',
            'population' => Region::find()->where(['id' => $ru])->sum('population'),
        ]);
        $russia->save();
        
        Region::updateAll(['stateId' => $russia->id], ['id' => $ru]);
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $russia->id,
            'data' => RegionCombiner::combine($russia->getRegions()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        
    }
    
}
