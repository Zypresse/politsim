<?php

namespace app\assets;

use Yii,
    yii\web\AssetBundle;

class LandingAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
	'yii\web\YiiAsset',
	'yii\bootstrap\BootstrapAsset',
    ];

}
