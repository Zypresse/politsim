<?php

namespace app\controllers;

use Yii,
    app\components\MyController,
    app\models\Tile;

/**
 * Description of MapController
 *
 * @author ilya
 */
class MapController extends MyController
{
    
    public function actionIndex()
    {
        $tiles = Tile::findAll();
        return $this->render('index', [
            'tiles' => $tiles
        ]);
    }
    
}
