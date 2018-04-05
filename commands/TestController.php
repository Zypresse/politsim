<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\government\State;
use app\models\map\Region;
use app\models\map\City;
use app\models\map\Tile;
use app\models\map\Polygon;
use app\components\TileCombiner;
use app\components\RegionCombiner;

/**
 * Description of TestController
 *
 * @author ilya
 */
class TestController extends Controller
{
    
    /**
     * regions and cities fix
     */
    public function actionRewriteIds()
    {
        
        $tran = Yii::$app->db->beginTransaction();
        try {
            $regions = Region::find()->all();
            foreach ($regions as $region) {
                $oldId = $region->id;
                $newReg = new Region($region->attributes);
                $newReg->id = $oldId + 10000;
                echo "changing {$region->name} id from {$oldId} to {$newReg->id}... ";
                if (!$newReg->save()) {
                    var_dump($newReg->getErrors());
                    $tran->rollBack();
                    return;
                }

                Tile::updateAll(['regionId' => $newReg->id], ['regionId' => $oldId]);
                echo "tiles updated... ";
                City::updateAll(['regionId' => $newReg->id], ['regionId' => $oldId]);
                $region->delete();
                echo "cities updated and old reg deleted".PHP_EOL;
            }
            $cities = City::find()->all();
            foreach ($cities as $city) {
                $oldId = $city->id;
                $newCity = new City($city->attributes);
                $newCity->id = $oldId + 10000;
                echo "changing {$city->name} id from {$oldId} to {$newCity->id}... ";
                if (!$newCity->save()) {
                    var_dump($newCity->getErrors());
                    $tran->rollBack();
                    return;
                }

                Tile::updateAll(['cityId' => $newCity->id], ['cityId' => $oldId]);
                echo "tiles updated... ";

                $city->delete();
                echo "old city deleted".PHP_EOL;
            }
            $tran->commit();
        } catch (\Exception $e) {
            $tran->rollBack();
            throw $e;
        }
        
    }
    
    public function actionResetIds()
    {
        
        $tran = Yii::$app->db->beginTransaction();
        try {
            $regions = Region::find()->all();
            $newId = 1;
            foreach ($regions as $region) {
                $oldId = $region->id;
                $newReg = new Region($region->attributes);
                $newReg->id = $newId;
                echo "changing {$region->name} id from {$oldId} to {$newReg->id}... ";
                if (!$newReg->save()) {
                    var_dump($newReg->getErrors());
                    $tran->rollBack();
                    return;
                }

                Tile::updateAll(['regionId' => $newReg->id], ['regionId' => $oldId]);
                echo "tiles updated... ";
                City::updateAll(['regionId' => $newReg->id], ['regionId' => $oldId]);
                $region->delete();
                echo "cities updated and old reg deleted".PHP_EOL;
                $newId++;
            }
            $cities = City::find()->all();
            $newId = 1;
            foreach ($cities as $city) {
                $oldId = $city->id;
                $newCity = new City($city->attributes);
                $newCity->id = $newId;
                echo "changing {$city->name} id from {$oldId} to {$newCity->id}... ";
                if (!$newCity->save()) {
                    var_dump($newCity->getErrors());
                    $tran->rollBack();
                    return;
                }

                Tile::updateAll(['cityId' => $newCity->id], ['cityId' => $oldId]);
                echo "tiles updated... ";

                $city->delete();
                echo "old city deleted".PHP_EOL;
                $newId++;
            }
            $tran->commit();
        } catch (\Exception $e) {
            $tran->rollBack();
            throw $e;
        }
    }
    
    const UNDEFINED_REGION = 1;
    
    /**
     * разбить антарктику на регионы
     */
    public function actionAntarctica()
    {
        
        $count = Tile::find()->where(['regionId' => self::UNDEFINED_REGION])->count();
        $n = 50000;
        $steps = ceil($count/$n);
        $tran = Yii::$app->db->beginTransaction();
        for ($i = 0; $i < $steps; $i++) {
            $query = Tile::find()->where(['regionId' => self::UNDEFINED_REGION])->orderBy(['x' => SORT_ASC, 'y' => SORT_ASC])->limit($n)->offset($n*$i);
            $region = new Region([
                'name' => 'Антарктида '.($i+1),
                'nameShort' => 'AN-'.($i+1),
                'population' => 0,
            ]);
            if (!$region->save()) {
                var_dump($region->getErrors());
                $tran->rollBack();
                return;
            }
            
            $polygon = new Polygon([
                'ownerType' => Polygon::TYPE_REGION,
                'ownerId' => $region->id,
                'data' => TileCombiner::combine($query),
            ]);
            if (!$polygon->save()) {
                var_dump($polygon->getErrors());
                $tran->rollBack();
                return;
            }
            
            echo "saved {$region->name}".PHP_EOL;
        }
        $tran->commit();
        
    }
    
}
