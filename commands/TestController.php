<?php

namespace app\commands;

use yii\console\Controller,
    app\components\TileCombiner,
    app\models;

class TestController extends Controller
{
    public function actionIndex()
    {
//        $conturs = TileCombiner::combine(Tile::find());
//        $conturs = models\Region::findByPk(1)->calcPolygon();
//        echo json_encode($conturs);
        echo models\Region::findByPk(1)->polygon;
        echo models\State::findByPk(1)->polygon;
    }
    
    public function actionActivate()
    {
        echo models\User::updateAll(['isInvited' => 1]);
    }
}
