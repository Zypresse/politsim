<?php

namespace app\commands;

use yii\console\Controller,
    app\components\TileCombiner,
    app\models\Tile,
    app\models\Region,
    app\models\State;

class TestController extends Controller
{
    public function actionIndex()
    {
//        $conturs = app\components\TileCombiner::combine(Tile::find());
//        $conturs = Region::findByPk(1)->calcPolygon();
//        echo json_encode($conturs);
        echo Region::findByPk(1)->polygon;
        echo State::findByPk(1)->polygon;
    }
}
