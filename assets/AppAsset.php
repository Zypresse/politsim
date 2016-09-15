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
        'https://fonts.googleapis.com/css?family=Open+Sans:400,600,800&subset=latin,cyrillic',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
//        'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',
//        'css/square.css',
//        'css/snackbar.min.css',
//        'css/spectrum.css',
        'css/dataTables.bootstrap.css',
        'css/bootstrap.css',
        'css/AdminLTE.css',
        'css/skin-black.css',
        'css/leaflet.css',
        'css/leaflet.label.css',
        'css/style.css',
    ];
    public $js = [
        'js/bootstrap.js',
        'js/app.js',
        'js/fullscreen.js',
//        'js/icheck.js',
//        'js/jquery.peity.min.js',
        'js/jquery-dateFormat.min.js',
        'js/serialize-object.js',
        'js/pace.min.js',
//        '//vk.com/js/api/xd_connection.js',
//        '//www.google.com/jsapi?autoload={\'modules\':[{\'name\':\'visualization\',\'version\':\'1\',\'packages\':[\'corechart\']}]}',
//        '//cdn.ckeditor.com/4.5.7/standard/ckeditor.js',
        'js/jquery.dataTables.min.js',
        'js/dataTables.bootstrap.min.js',
        'js/leaflet.js',
        'js/leaflet.label.js',
        'js/script_all.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\widgets\ActiveFormAsset',
        'franciscomaya\sceditor\SCEditorAsset',
    ];
    
    public function __construct($config = array()) {
        
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInvited) {
            $this->js[] = 'js/script_authorized.js';
        }
        
        if (Yii::$app->user->id > 3) {
            $this->js[] = '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
        }
        
        return parent::__construct($config);
    }
}
