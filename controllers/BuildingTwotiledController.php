<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\economics\units\BuildingTwotiled,
    app\models\economics\units\BuildingTwotiledProto;

/**
 * 
 */
final class BuildingTwotiledController extends MyController
{
    
    public function actionIndex(int $id)
    {
        $building = $this->loadBuilding($id);
        return $this->render('view', [
            'building' => $building,
            'user' => $this->user,
        ]);
    }
    
    public function actionFutureInfo(int $protoId, int $size)
    {
        $proto = BuildingTwotiledProto::instantiate($protoId);
        return $this->render('future-info', [
            'proto' => $proto,
            'size' => $size,
        ]);
    }
    
    /**
     * 
     * @param integer $id
     * @return Building
     * @throws NotFoundHttpException
     */
    private function loadBuilding(int $id)
    {
        $building = BuildingTwotiled::findByPk($id);
        if (is_null($building)) {
            throw new NotFoundHttpException(Yii::t('app', 'Building not found'));
        }
        return $building;
    }
    
}
