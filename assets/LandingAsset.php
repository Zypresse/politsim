<?php

namespace app\assets;

use Yii,
    yii\web\AssetBundle;

class LandingAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
	'/css/landing.css',
    ];
    public $js = [
	'/js/jquery.countdown.min.js',
	'/js/landing.js',
    ];
    public $depends = [];

}
