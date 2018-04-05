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
        $countRaw = count($rawData);
        echo "regions loaded ($countRaw regions)" . PHP_EOL;
        $data = [];
        foreach ($rawData as $region) {
            $data[] = [
                'id' => $region->id,
                'name' => $region->name,
                'nameShort' => $region->nameShort,
                'population' => $region->population,
            ];
        }

        $count = Yii::$app->db->createCommand()->batchInsert(Region::tableName(), ['id', 'name', 'nameShort', 'population'], $data)->execute();
        $autoincrement = $count+1;
        Yii::$app->db->createCommand("ALTER SEQUENCE regions_id_seq RESTART WITH {$autoincrement}")->execute();
        echo "regions inserted ($count regions)" . PHP_EOL;
    }

    /**
     * load default cities
     */
    private function loadCities()
    {
        $rawData = json_decode(file_get_contents(Yii::$app->basePath . '/data/default/cities.json'));
        $countRaw = count($rawData);
        echo "cities loaded ($countRaw cities)" . PHP_EOL;
        $data = [];
        foreach ($rawData as $city) {
            $data[] = [
                'id' => $city->id,
                'name' => $city->name,
                'nameShort' => $city->nameShort,
                'regionId' => $city->regionId,
                'population' => $city->population,
            ];
        }

        $count = Yii::$app->db->createCommand()->batchInsert(City::tableName(), ['id', 'name', 'nameShort', 'regionId', 'population'], $data)->execute();
        $autoincrement = $count+1;
        Yii::$app->db->createCommand("ALTER SEQUENCE cities_id_seq RESTART WITH {$autoincrement}")->execute();
        echo "cities inserted ($count cities)" . PHP_EOL;
    }

    /**
     * 2) wipe and load tiles scheme
     */
    public function actionReloadTiles()
    {
        $parts = 16; // TODO: files list
        $db = Yii::$app->db;
        $db->createCommand()->setSql("TRUNCATE TABLE {$db->quoteTableName(Tile::tableName())} CASCADE")->execute();
        for ($i = 0; $i < $parts; $i++) {
            $rawData = json_decode(file_get_contents(Yii::$app->basePath . '/data/default/tiles/part' . $i . '.json'));
            $countRaw = count($rawData);
            echo "part #$i loaded ($countRaw tiles)" . PHP_EOL;
            $data = [];
            foreach ($rawData as $tile) {
                $data[] = [
                    'x' => $tile->x,
                    'y' => $tile->y,
                    'lat' => $tile->lat,
                    'lon' => $tile->lon,
                    'biome' => $tile->biome,
                    'regionId' => $tile->regionId,
                    'cityId' => $tile->cityId,
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
        $db = Yii::$app->db;
        $db->createCommand()->setSql("TRUNCATE TABLE {$db->quoteTableName(Polygon::tableName())} CASCADE")->execute();
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
            try {
                $polygon = new Polygon([
                    'ownerType' => Polygon::TYPE_REGION,
                    'ownerId' => $region->id,
                    'data' => TileCombiner::combine($region->getTiles()),
                ]);
                if (!$polygon->save()) {
                    var_dump($polygon->getErrors()); die();
                }
                echo " saved".PHP_EOL;
            } catch (\yii\console\Exception $e) {
                echo " error: {$e->getMessage()}".PHP_EOL;
                continue;
            }
        }
    }
    
    /**
     * 5) Calc regions and cities areas
     */
    public function actionCalcPolygonsArea()
    {
        $cities = City::findAll();
        /* @var $city City */
        foreach ($cities as $city) {
            echo $city->name;
            if (!$city->getTiles()->exists()) {
                echo " have no tiles".PHP_EOL;
                continue;
            }
            $city->area = 0;
            foreach ($city->tiles as $tile) {
                $city->area += $tile->area;
            }
            $city->area = round($city->area);
            $city->save();
            echo " saved".PHP_EOL;
        }
        
        $regions = Region::findAll();
        /* @var $region Region */
        foreach ($regions as $region) {
            echo $region->name;
            if (!$region->getTiles()->exists()) {
                echo " have no tiles".PHP_EOL;
                continue;
            }
            $region->area = 0;
            foreach ($region->tiles as $tile) {
                $region->area += $tile->area;
            }
            $region->area = round($region->area);
            $region->save();
            echo " saved".PHP_EOL;
        }
    }

}
