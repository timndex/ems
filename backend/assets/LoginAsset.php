<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'assets/proui/css/login.css',
        'assets/proui/css/bootstrap.mins.css',
 


    ];
    public $img =[
   
    ];
    public $js = [
        'assets/proui/js/vendor/jquery.min.js',
      'assets/proui/js/vendor/bootstrap.min.js',
    
    ];
    public $depends = [
      'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
