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
    
    const R = 0.1;

    private static function getLat($x,$y)
    {
        $lat = $y%2 == 0 ? 0 : static::R*0.886;
        if ($x > 0) {
            for ($i = 0; $i < $x; $i++) {
                $lat += static::R*0.866*2*static::correctX($lat);
            }
        } else {
            for ($i = 0; $i > $x; $i--) {
                $lat -= static::R*0.866*2*static::correctX($lat);
            }
        }
        return $lat;
    }

    private static function getLng($x,$y) {
        return $y*static::R*1.5;
    }

    private static function correctX($x) {
        return round(cos($x*0.0175)*41000/360 / 111.1111,4);
    }

    
    public function actionNordpole()
    {
        $data = [];
        for ($x = 942; $x < 1026; $x++) {
            for ($y = -1200; $y <= 1200; $y++) {
                $check = Tile::find()->where(['x' => $x, 'y' => $y])->exists();
                if (!$check) {
                    $data[] = [
                        'x' => $x,
                        'y' => $y,
                        'lat' => round(static::getLat($x, $y) * Tile::LAT_LON_FACTOR),
                        'lon' => round(static::getLng($x, $y) * Tile::LAT_LON_FACTOR),
                        'biome' => Tile::BIOME_WATER,
                        'population' => 0,
                    ];
                }
            }
        }
        echo "prepared ".count($data).PHP_EOL;
        echo "saved ".Tile::getDb()->createCommand()->batchInsert(Tile::tableName(), ['x', 'y', 'lat', 'lon', 'biome', 'population'], $data)->execute().PHP_EOL;
    }
    
}
