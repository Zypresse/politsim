<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii,
    yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        '//fonts.googleapis.com/css?family=Roboto+Condensed:400italic,400,700&subset=cyrillic,latin',
//        'css/plusstrap.min.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,600,800&subset=latin,cyrillic',
        'css/application.css',
        'css/square.css',
        'css/snackbar.min.css',
        'css/spectrum.css',
        'css/jquery-jvectormap-2.0.3.css',
        'css/style.css',
    ];
    public $js = [
        'js/jquery-1.11.3.min.js',
        'js/jquery-ui.min.js',
        'js/bootstrap.min.js',
        'js/fullscreen.js',
        'js/jquery-jvectormap-2.0.3.min.js',
        'js/jqtablepagination.js',
        'js/icheck.js',
        'js/jquery.peity.min.js',
        'js/jquery-dateFormat.min.js',
        '//vk.com/js/api/xd_connection.js',
        '//www.google.com/jsapi?autoload={\'modules\':[{\'name\':\'visualization\',\'version\':\'1\',\'packages\':[\'corechart\']}]}',
        '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
        'js/script_all.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function __construct($config = array()) {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->invited) {
            $this->js[] = 'js/script_authorized.js';
        }
        return parent::__construct($config);
    }
}
