<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\map\Tile;
use app\models\map\Region;
use app\models\map\City;
use app\models\map\Polygon;
use app\components\TileCombiner;

/**
 * Description of WipeController
 *
 * @author ilya
 */
class WipeController extends Controller
{

    /**
     * 1) wipe and load default regions and cities
     */
    public function actionReloadRegionsAndCities()
    {
        $db = Yii::$app->db;
        $db->createCommand()->setSql("TRUNCATE TABLE {$db->quoteTableName(Region::tableName())} CASCADE")->execute();
        $this->loadRegions();
        $this->loadCities();
    }

    /**
     * load default regions
     */
    private function loadRegions()
    {
        $rawData = json_decode(file_get_contents(Yii::$app->basePath . '/data/default/regions.json'));
        array_pop($rawData); // remove first empty element
        $countRaw = count($rawData);
        echo "regions loaded ($countRaw regions)" . PHP_EOL;
        $data = [];
        foreach ($rawData as $region) {
            $data[] = [
                'id' => $region[0],
                'name' => $region[1],
                'nameShort' => $region[2],
                'population' => $region[3],
            ];
        }

        $count = Yii::$app->db->createCommand()->batchInsert(Region::tableName(), ['id', 'name', 'nameShort', 'population'], $data)->execute();
        echo "regions inserted ($count regions)" . PHP_EOL;
    }

    /**
     * load default cities
     */
    private function loadCities()
    {
        $rawData = json_decode(file_get_contents(Yii::$app->basePath . '/data/default/cities.json'));
        array_pop($rawData); // remove first empty element
        $countRaw = count($rawData);
        echo "cities loaded ($countRaw cities)" . PHP_EOL;
        $data = [];
        foreach ($rawData as $city) {
            $data[] = [
                'id' => $city[0],
                'name' => $city[1],
                'nameShort' => $city[2],
                'regionId' => $city[3],
                'population' => $city[4],
            ];
        }

        $count = Yii::$app->db->createCommand()->batchInsert(City::tableName(), ['id', 'name', 'nameShort', 'regionId', 'population'], $data)->execute();
        echo "cities inserted ($count cities)" . PHP_EOL;
    }

    /**
     * 2) wipe and load tiles scheme
     */
    public function actionReloadTiles()
    {
        $db = Yii::$app->db;
        $db->createCommand()->setSql("TRUNCATE TABLE {$db->quoteTableName(Tile::tableName())} CASCADE")->execute();
        for ($i = 0; $i < 34; $i++) {
            $rawData = json_decode(file_get_contents(Yii::$app->basePath . '/data/default/tiles/part' . $i . '.json'));
            array_pop($rawData); // remove first empty element
            $countRaw = count($rawData);
            echo "part #$i loaded ($countRaw tiles)" . PHP_EOL;
            $data = [];
            foreach ($rawData as $tile) {
                $biome = 0;
                if ($tile[4]) {
                    $biome += Tile::BIOME_WATER;
                }
                if ($tile[5]) {
                    $biome += Tile::BIOME_LAND;
                }
                // удалённые города которые какого-то хуя остались в тайлах
                $restrictedCities = [151, 152, 155, 150, 156, 154, 157, 153, 144, 145, 146, 137, 147, 142, 141, 138, 143, 140, 139, 136, 135, 134, 149, 148, 73];
                // удалённые регионы которые какого-то хуя остались в тайлах
                $restrictedRegions = [341];
                
                $data[] = [
                    'x' => $tile[0],
                    'y' => $tile[1],
                    'lat' => round($tile[2] * Tile::LAT_LON_FACTOR),
                    'lon' => round($tile[3] * Tile::LAT_LON_FACTOR),
                    'biome' => $biome,
                    'regionId' => in_array($tile[6], $restrictedRegions) ? null : $tile[6],
                    'cityId' => in_array($tile[7], $restrictedCities) ? null : $tile[7],
                ];
            }
            $count = $db->createCommand()->batchInsert(Tile::tableName(), ['x', 'y', 'lat', 'lon', 'biome', 'regionId', 'cityId'], $data)->execute();
            echo "part #$i inserted ($count tiles)" . PHP_EOL;
        }
    }
    
    /**
     * 3) calculate tiles population
     */
    public function actionCalcTilesPopulation()
    {
        foreach (Region::find()->all() as $region) {
            $regionTilesCount = (int)$region->getTiles()->count();
            if ($regionTilesCount === 0) {
                continue;
            }
            $population = $region->population;
            echo $region->name.' — '.$population.' / '.$regionTilesCount.PHP_EOL;
            foreach ($region->cities as $city) {
                $cityTilesCount = (int)$city->getTiles()->count();
                if ($cityTilesCount === 0) {
                    continue;
                }
                echo '  '.$city->name.' — '.$city->population.' / '.$cityTilesCount.PHP_EOL;
                
                Tile::updateAll(['population' => round($city->population/$cityTilesCount)], ['cityId' => $city->id]);
                $population -= $city->population;
                $regionTilesCount -= $cityTilesCount;
            }
            Tile::updateAll(['population' => round($population/$regionTilesCount)], ['cityId' => null, 'regionId' => $region->id]);
        }
    }
    
    /**
     * 4) Combine regions and cities polygons
     */
    public function actionCombinePolygons()
    {
        $cities = City::findAll();
        /* @var $city City */
        foreach ($cities as $city) {
            echo $city->name;
            if ($city->getPolygon()->exists()) {
                echo " skipped".PHP_EOL;
                continue;
            }
            if (!$city->getTiles()->exists()) {
                echo " have no tiles".PHP_EOL;
                continue;
            }
            $polygon = new Polygon([
                'ownerType' => Polygon::TYPE_CITY,
                'ownerId' => $city->id,
                'data' => TileCombiner::combine($city->getTiles()),
            ]);
            if (!$polygon->save()) {
                var_dump($polygon->getErrors(), $polygon->data); die();
            }
            echo " saved".PHP_EOL;
        }
        
        $regions = Region::findAll();
        /* @var $region Region */
        foreach ($regions as $region) {
            echo $region->name;
            if ($region->getPolygon()->exists()) {
                echo " skipped".PHP_EOL;
                continue;
            }
            if (!$region->getTiles()->exists()) {
                echo " have no tiles".PHP_EOL;
                continue;
            }
            $polygon = new Polygon([
                'ownerType' => Polygon::TYPE_REGION,
                'ownerId' => $region->id,
                'data' => TileCombiner::combine($region->getTiles()),
            ]);
            if (!$polygon->save()) {
                var_dump($polygon->getErrors()); die();
            }
            echo " saved".PHP_EOL;
        }
    }

}
