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
        'css/plusstrap.min.css',
        'css/square.css',
        'css/snackbar.min.css',
        '//fonts.googleapis.com/css?family=Roboto+Condensed:400italic,400,700&subset=cyrillic,latin',
        'css/style.css'
    ];
    public $js = [
        'js/jquery.js',
        'js/bootstrap.min.js',
        'js/fullscreen.js',
        'js/jqvectormap.js',
        'js/jqtablepagination.js',
        'js/icheck.js',
        'js/snackbar.min.js',
        'js/jquery.peity.min.js',
        'js/jquery-dateFormat.min.js',
        '//vk.com/js/api/xd_connection.js',
        'js/script_all.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function __construct($config = array()) {
        if (!Yii::$app->user->isGuest) {
            $this->js[] = 'js/script_authorized.js';
        }
        return parent::__construct($config);
    }
}
