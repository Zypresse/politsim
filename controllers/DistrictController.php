<?php

namespace app\controllers;

use Yii,
    app\controllers\base\MyController,
    app\models\politics\elections\ElectoralDistrict,
    yii\web\NotFoundHttpException;

/**
 * 
 */
final class DistrictController extends MyController
{
    
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
        
        /* @var $districts ElectoralDistrict[] */
        $districts = ElectoralDistrict::find()->where(['in', 'id', $ids])->all();
        if (!count($districts)) {
            throw new NotFoundHttpException(Yii::t('app', 'Electoral district not found'));
        }
        
        $this->result = [];
        foreach ($districts as $district) {
            foreach ($district->tiles as $tile) {
                $this->result[] = [
                    'id' => $tile->id,
                    'lat' => $tile->lat,
                    'lon' => $tile->lon,
                    'x' => $tile->x,
                    'y' => $tile->y,
                    'districtId' => $tile->electoralDistrictId,
                    'coords' => $tile->coords,
                ];
            }
        }
        
        return $this->_r();
    }
    
    public function actionPolygons($ids)
    {
        list($id1, $id2) = explode(',', $ids);
        $district1 = $this->getDistrict($id1);
        $district2 = $this->getDistrict($id2);
        $this->result = [
            [
                'id' => $district1->id,
                'coords' => json_decode($district1->polygon),
            ],
            [
                'id' => $district2->id,
                'coords' => json_decode($district2->polygon),
            ],
        ];
        return $this->_r();
    }
    
    private function getDistrict(int $id)
    {
        $district = ElectoralDistrict::findByPk($id);
        if (is_null($district)) {
            throw new NotFoundHttpException(Yii::t('app', 'Electoral district not found'));
        }
        return $district;
    }
    
}
