<?php

namespace app\commands;

use yii\console\Controller,
    app\models\licenses\LicenseRule;

class TestController extends Controller
{
    public function actionIndex()
    {
        echo "test";
    }
}
