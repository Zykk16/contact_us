<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/owl.carousel.min.css',
        'css/owl.theme.default.min.css',
        'css/owl.theme.green.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/owl.carousel.min.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
