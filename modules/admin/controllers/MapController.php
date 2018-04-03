<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Response;
use app\modules\admin\controllers\base\AdminController;
use app\models\map\City;
use app\models\map\Region;
use app\models\map\Tile;

/**
 * Description of MapController
 *
 * @author ilya
 */
class MapController extends AdminController
{
    
    const TYPE_CITY = 'city';
    const TYPE_REGION = 'region';
    const TYPE_LAND = 'land';
    const TYPE_WATER = 'water';
    
    public function actionIndex()
    {
        return $this->render('index', [
            'cities' => City::find()->with('polygon')->all(),
            'regions' => Region::find()->with('polygon')->all(),
        ]);
    }
    
    public function actionDebug()
    {
        return $this->render('debug', [
            'tiles' => Tile::find()->where(['and', ['>', 'x', -437], ['regionId' => 3]])->all(),
        ]);
    }
    
    public function actionLand()
    {
        return $this->render('biome', [
            'type' => self::TYPE_LAND,
        ]);
    }
    
    public function actionWater()
    {
        return $this->render('biome', [
            'type' => self::TYPE_WATER,
        ]);
    }
    
    public function actionTiles($minLat, $maxLat, $minLng, $maxLng, $type, $subType = 0)
    {
        $query = Tile::find()
                ->andWhere(['BETWEEN', 'lat', round($minLat*Tile::LAT_LON_FACTOR),  round($maxLat*Tile::LAT_LON_FACTOR)])
                ->andWhere(['BETWEEN', 'lon', round($minLng*Tile::LAT_LON_FACTOR),  round($maxLng*Tile::LAT_LON_FACTOR)]);
        
        if (!in_array($type, [self::TYPE_LAND, self::TYPE_WATER])) {
            $query->andWhere(['>', 'biome', 1]);
        }
                
        $tiles = $query->all();
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => $this->getTilesData($tiles, $type, $subType)];
    }
    
    /**
     * 
     * @param Tile[] $tiles
     * @param string $type
     * @param integer $id
     * @return array
     */
    private function getTilesData($tiles, string $type, int $id)
    {
        $data = [];
        /* @var $tile Tile */
        foreach ($tiles as $tile) {
            $occupied = false;
            $disabled = false;
            switch ($type) {
                case self::TYPE_CITY:
                    $occupied = $tile->cityId && (int)$tile->cityId === $id;
                    $disabled = $tile->cityId && (int)$tile->cityId !== $id;
                    break;
                case self::TYPE_REGION:
                    $occupied = $tile->regionId && (int)$tile->regionId === $id;
                    $disabled = $tile->regionId && (int)$tile->regionId !== $id;
                    break;
                case self::TYPE_LAND:
                    $occupied = (int)$tile->biome === Tile::BIOME_LAND;
                    break;
                case self::TYPE_WATER:
                    $occupied = (int)$tile->biome === Tile::BIOME_WATER;
                    break;
            }
            
            $data[] = [
                'id' => $tile->id,
                'occupied' => $occupied,
                'disabled' => $disabled,
                'coords' => $tile->coords,
            ];
        }
        return $data;
    }
    
    public function actionSave()
    {
        $type = Yii::$app->request->post('type');
        $id = (int) Yii::$app->request->post('subType');
        $selected = $this->explodePost('selected');
        $deleted = $this->explodePost('deleted');
        
        $cUpdated = 0;
        $cDeleted = 0;
        switch ($type) {
            case self::TYPE_CITY:
                $cUpdated = Tile::updateAll(['"cityId"' => $id], ['id' => $selected]);
                $cDeleted = Tile::updateAll(['"cityId"' => null], ['id' => $deleted]);
                break;
            case self::TYPE_REGION:
                $cUpdated = Tile::updateAll(['"regionId"' => $id], ['id' => $selected]);
                $cDeleted = Tile::updateAll(['"regionId"' => null], ['id' => $deleted]);
                break;
            case self::TYPE_LAND:
                $cUpdated = Tile::updateAll(['biome' => Tile::BIOME_LAND], ['id' => $selected]);
                $cDeleted = Tile::updateAll(['biome' => Tile::BIOME_WATER], ['id' => $deleted]);
                break;
            case self::TYPE_WATER:
                $cUpdated = Tile::updateAll(['biome' => Tile::BIOME_WATER], ['id' => $selected]);
                $cDeleted = Tile::updateAll(['biome' => Tile::BIOME_LAND], ['id' => $deleted]);
                break;
        }
        
        return "Upd tiles: $cUpdated saved, $cDeleted deleted";
        
    }
    
    /**
     * 
     * @param string $field
     * @return array
     */
    private function explodePost($field)
    {
        $data = Yii::$app->request->post($field);
        return $data ? explode(',', $data) : [];
    }
    
}
