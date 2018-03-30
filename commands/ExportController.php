<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\map\City;
use app\models\map\Region;
use app\models\map\Tile;

/**
 * Description of ExportController
 *
 * @author ilya
 */
class ExportController extends Controller
{
    
    /**
     * Export cities data to cities.json
     */
    public function actionCities()
    {
        $data = [];
        $cities = City::findAll();
        /* @var $city City */
        foreach ($cities as $city) {
            $data[] = [
                'id' => $city->id,
                'name' => $city->name,
                'nameShort' => $city->nameShort,
                'population' => $city->population,
                'regionId' => $city->regionId,
            ];
        }
        file_put_contents(Yii::$app->basePath . '/data/default/cities.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        echo count($data)." cities exported".PHP_EOL;
    }
    
    /**
     * Export regions data to regions.json
     */
    public function actionRegions()
    {
        $data = [];
        $regions = Region::findAll();
        /* @var $region Region */
        foreach ($regions as $region) {
            $data[] = [
                'id' => $region->id,
                'name' => $region->name,
                'nameShort' => $region->nameShort,
                'population' => $region->population,
            ];
        }
        file_put_contents(Yii::$app->basePath . '/data/default/regions.json', json_encode($data, JSON_UNESCAPED_UNICODE));
        echo count($data)." regions exported".PHP_EOL;
    }
    
    /**
     * Export tiles data to tiles/partXX.json
     */
    public function actionTiles()
    {
        $step = 300000;
        $count = Tile::find()->count();
        $parts = ceil($count/$step);
        
        for ($i = 0; $i < $parts; $i++) {
            $data = [];
            $tiles = Tile::find()->orderBy(['id' => SORT_ASC])->limit($step)->offset($i*$step)->all();
            /* @var $tile Tile */
            foreach ($tiles as $tile) {
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
            file_put_contents(Yii::$app->basePath . "/data/default/tiles/part{$i}.json", json_encode($data, JSON_UNESCAPED_UNICODE));
            echo count($data)." tiles exported".PHP_EOL;
        }
    }
    
}
