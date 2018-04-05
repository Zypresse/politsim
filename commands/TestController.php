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
        $width = 100;
        
        $tran = Yii::$app->db->beginTransaction();
        
        for ($y = -1200, $i = 0; $y <= 1200; $y+=$width, $i++) {
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
            
            echo "{$region->name} created...";
            
            $count = Tile::updateAll(['regionId' => $region->id], ['and', ['between', 'y', $y, $y+$width-1], ['regionId' => self::UNDEFINED_REGION]]);
            
            echo "saved {$count} tiles".PHP_EOL;
        }
        $tran->commit();
        
    }
    
}
