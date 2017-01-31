<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    yii\helpers\Html,
    app\components\MyController,
    app\models\politics\Region;

/**
 * 
 */
class RegionController extends MyController
{
    
    public function actionIndex($id)
    {
        
        $region = $this->getRegion($id);
        
        return $this->render('view', [
            'region' => $region,
            'user' => $this->user
        ]);
    }
    
    public function actionConstitution($id)
    {
        
        $region = $this->getRegion($id);
        
        return $this->render('constitution', [
            'region' => $region,
            'constitution' => $region->constitution,
            'user' => $this->user
        ]);
    }
    
    public function actionConstitutionValue(int $regionId, int $type)
    {
        $region = $this->getRegion($regionId);
        $article = $region->constitution->getArticleByTypeOrEmptyModel($type);
        $this->result = $article->getPublicAttributes();
        return $this->_r();
    }
    
    public function actionTiles(int $id = null, $ids = null)
    {
        
        if (!$id && !$ids) {
            return $this->_r(Yii::t('app', 'Invalid parametres'));
        }
        
        if ($id) {
            $ids = [$id];
        } else {
            $ids = explode(',', $ids);
        }
        
        $regions = Region::find()->where(['in', 'id', $ids])->all();
        if (!count($regions)) {
            throw new NotFoundHttpException(Yii::t('app', 'Region not found'));
        }
        
        $this->result = [];
        foreach ($regions as $region) {
            foreach ($region->tiles as $tile) {
                $this->result[] = [
                    'id' => $tile->id,
                    'lat' => $tile->lat,
                    'lon' => $tile->lon,
                    'x' => $tile->x,
                    'y' => $tile->y,
                    'regionId' => $tile->regionId,
                    'coords' => $tile->coords,
                ];
            }
        }
        
        return $this->_r();
    }
    
    public function actionPolygons($ids)
    {
        list($id1, $id2) = explode(',', $ids);
        $region1 = $this->getRegion($id1);
        $region2 = $this->getRegion($id2);
        $this->result = [
            [
                'id' => $region1->id,
                'coords' => json_decode($region1->polygon),
            ],
            [
                'id' => $region2->id,
                'coords' => json_decode($region2->polygon),
            ],
        ];
        return $this->_r();
    }
    
    public function actionCities(int $id)
    {
        $region = $this->getRegion($id);
        $this->result = [];
        foreach ($region->cities as $city) {
            $this->result[] = [
                'id' => $city->id,
                'name' => Html::encode($city->name),
            ];
        };
        return $this->_r();
    }


    private function getRegion(int $id)
    {
        $region = Region::findByPk($id);
        if (is_null($region)) {
            throw new NotFoundHttpException(Yii::t('app', 'Region not found'));
        }
        return $region;
    }
    
}
