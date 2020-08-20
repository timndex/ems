<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
         '../assets/proui/css/plugins.css',
         '../assets/proui/css/main.css',
         '../assets/proui/css/themes.css',
         '../assets/proui/css/dataTables.min.css',
        
    ];
    public $js = [
        '../assets/proui/js/dataTables.min.js',
        '../assets/proui/js/dataTables.buttons.min.js',
        '../assets/proui/js/buttons.print.min.js',
        //'../assets/proui/js/vendor/jquery.min.js',
        '../assets/proui/js/vendor/loadingoverlay.min.js',
        //'../assets/proui/js/vendor/bootstrap.min.js',
        '../assets/proui/js/plugins.js',
         '../assets/proui/js/app.js',
         '../assets/proui/js/vendor/modernizr.min.js',
         '../assets/proui/js/pages/index.js',
         '../assets/proui/js/sum().js',
        //'../assets/proui/js/yadcf/dataTablesyadcf.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
         'yidas\yii\fontawesome\FontawesomeAsset',
         'yii\bootstrap\BootstrapPluginAsset' ,

         
    ];
}
