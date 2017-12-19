<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Main controller
 */
class SiteController extends Controller
{

    public function actionIndex()
    {
	return $this->render('landing');
    }

}
