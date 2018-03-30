<?php

namespace app\assets;

use Yii,
    yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
	'css/jquery-ui-theme.css',
	'css/dataTables.bootstrap.css',
	'css/skin-black.css',
	'css/leaflet.css',
	'css/icheck-blue.css',
	'css/style.css',
    ];
    public $js = [
	'js/fullscreen.js',
	'js/jquery-dateFormat.min.js',
	'js/icheck.min.js',
//      'js/serialize-object.js',
	'js/jquery.dataTables.min.js',
	'js/dataTables.bootstrap.min.js',
	'js/leaflet.js',
	'js/leaflet.geometryutil.js',
	'js/leaflet-geodesy.js',
	'js/app.js',
	'js/script.js',
    ];
    public $depends = [
	'yii\web\YiiAsset',
	'yii\jui\JuiAsset',
	'yii\bootstrap\BootstrapAsset',
	'kartik\base\WidgetAsset',
	'dmstr\web\AdminLteAsset',
    ];

}
