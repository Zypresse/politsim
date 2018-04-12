<?php

namespace app\assets;

use Yii,
    yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
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
	'js/lib/fullscreen.js',
	'js/lib/jquery-dateFormat.min.js',
	'js/lib/icheck.min.js',
//      'js/lib/serialize-object.js',
	'js/lib/jquery.dataTables.min.js',
	'js/lib/dataTables.bootstrap.min.js',
	'js/lib/leaflet.js',
	'js/lib/leaflet.geometryutil.js',
	'js/lib/leaflet-geodesy.js',
        // для текста вдоль полилиний
//	'js/lib/leaflet.textpath.js',
//	'js/lib/app.js',
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
