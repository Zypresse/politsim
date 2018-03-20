<?php

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\base\AdminController;
use app\models\map\City;
use app\models\map\Region;

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
    
}
