<?php

namespace app\controllers;

use Yii,
    yii\web\NotFoundHttpException,
    app\components\MyController,
    app\models\economics\units\Building,
    app\models\economics\units\BuildingProto;

/**
 * 
 */
final class BuildingController extends MyController
{
    
    public function actionFutureInfo(int $protoId, int $size)
    {
        $proto = BuildingProto::instantiate($protoId);
        return $this->render('future-info', [
            'proto' => $proto,
            'size' => $size,
        ]);
    }
    
}
