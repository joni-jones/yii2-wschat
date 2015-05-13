<?php

namespace jones\wschat;

use yii\web\AssetBundle;

/**
 * Class ChatLibAsset
 * @package jones\wschat
 */
class ChatLibAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'fontawesome/css/font-awesome.min.css',
        'pnotify/pnotify.core.css',
    ];
    public $js = [
        'underscore/underscore-min.js',
        'backbone/backbone.js',
        'jquery-cookie/jquery.cookie.js',
        'pnotify/pnotify.core.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}