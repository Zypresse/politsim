<?php

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\base\Controller;

/**
 * Description of MapController
 *
 * @author ilya
 */
class MapController extends Controller
{
    
    public $layout = 'admin';
    
    public function actionIndex()
    {
        return 'map';
    }
    
}
