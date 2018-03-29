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
    
    public function actionIndex()
    {
        return $this->render('index', [
            'cities' => City::findAll(),
//            'regions' => Region::findAll(),
        ]);
    }
    
    public function actionTiles($minLat, $maxLat, $minLng, $maxLng, $type, $subType = null)
    {
        $tiles = Tile::find()
                ->andWhere(['BETWEEN', 'lat', round($minLat*Tile::LAT_LON_FACTOR),  round($maxLat*Tile::LAT_LON_FACTOR)])
                ->andWhere(['BETWEEN', 'lon', round($minLng*Tile::LAT_LON_FACTOR),  round($maxLng*Tile::LAT_LON_FACTOR)])
                ->andWhere(['>', 'biome', 1])
                ->all();
        $data = [];
        /* @var $tile Tile */
        foreach ($tiles as $tile) {
            $data[] = [
                'id' => $tile->id,
                'occupied' => $tile->cityId && $tile->cityId == $subType,
                'disabled' => $tile->cityId && $tile->cityId != $subType,
                'coords' => $tile->coords,
            ];
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => $data];
    }
    
    private function explodePost($field)
    {
        $data = Yii::$app->request->post($field);
        return $data ? explode(',', $data) : [];
    }
    
    public function actionSave()
    {
        $type = (int) Yii::$app->request->post('type');
        $subType = (int) Yii::$app->request->post('subType');
        $selected = $this->explodePost('selected');
        $deleted = $this->explodePost('deleted');
        
        
        $cUpdated = Tile::updateAll(['cityId' => $subType], ['id' => $selected]);
        $cDeleted = Tile::updateAll(['cityId' => null], ['id' => $deleted]);
        
        return "Upd tiles: $cUpdated saved, $cDeleted deleted";
        
    }
    
}
