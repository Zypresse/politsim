<?php

namespace app\commands;

use yii\console\Controller,
    app\models\Tile,
    app\models\Region;

class TestController extends Controller
{
    public function actionIndex()
    {
//        $conturs = Tile::union(Tile::find());
//        $conturs = Region::findByPk(1)->calcPolygon();
//        echo json_encode($conturs);
        echo Region::findByPk(1)->polygon;
    }
}
