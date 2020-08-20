<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                        'yii\web\JqueryAsset' => [
                            // 'js' => [
                            //     YII_ENV_DEV ? '/assets/proui/js/vendor/jquery.min.js' : '/assets/proui/js/vendor/jquery.min.js'
                            // ]
                        ],
                        // 'yii\bootstrap\BootstrapAsset' => [
                        //     'css' => [
                        //         YII_ENV_DEV ? 'css/bootstrap.css' :         'css/bootstrap.min.css',
                        //     ]
                        // ],
                        'yii\bootstrap\BootstrapPluginAsset' => [
                            'js' => [
                                YII_ENV_DEV ? '/assets/proui/js/vendor/bootstrap.min.js' : '/assets/proui/js/vendor/bootstrap.min.js',
                            ]
                        ]
            ],
        ],

        

        
        //setting landing site
        //  'request'=>[
        //     'baseUrl'=>'/backend',
        // ],
        // 'urlManager'=>[
        //     'scriptUrl'=>'/backend/index.php',
        // ],
        
        // 'urlManager' => [
        //     'enablePrettyUrl' => true,
        //     'showScriptName' => false,
        //     'rules' => [
        //     ],
        // ],

        
    ],
    'modules' => [
        'gridview' =>  [
        'class' => '\kartik\grid\Module',
    ],
],
    'params' => $params,
];
