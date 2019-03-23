<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'googleDrive' => [
            'class' => 'lhs\Yii2FlysystemGoogleDrive',
            'clientId'     => '848630762900-6d0tt9kfc5gnm3vdg62got9o9khlj9l0.apps.googleusercontent.com',
            'clientSecret' => 'reG72krsEUyBulm0TWz6RvwF',
            'refreshToken' => '1/K8Pm9PVMa8kPKWvxrTv32DYS1tNKLL3u-v5bhrZ2qpY',
            // 'rootFolderId' => 'xxx ROOT FOLDER ID xxx'
        ],
//            access: ya29.GlvVBtGwGmq_9pQNWkHydV1J5skvydj17c6kim9cUxy0x33xQKXEWy6pqu9AoMRQCJ9cYd1-Jp7IdiHGGYErxs87RIVJBrb9aE-DsCGNXNLIjFClWOVx8aBJz_B8
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                "<controller:\w+>/<action:\w+>/<id:\d+>" => "<controller>/<action>",
                "<controller:\w+>/<action:\w+>" => "<controller>/<action>",
                '<module:\w+>/<action:\w+>/<id:\d+>' => '<module>/default/<action>',
                '<module:\w+>/<controller:\w+>' => '<module>/<controller>/index',
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],

            ],
        ],
    ],
    'params' => $params,
];
