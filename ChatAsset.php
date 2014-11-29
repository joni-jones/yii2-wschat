<?php
namespace jones\wschat;

use Yii;
use yii\web\AssetBundle;

/**
 * Class ChatAsset
 * @package jones\wschat
 */
class ChatAsset extends AssetBundle
{
    public $css = [
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.css',
        'css/bootstrap.min.css',
        'css/style.css'
    ];

    public $js = [
        'js/require.js',
        'js/app.js',
    ];

     public $depends = [
         '\yii\bootstrap\BootstrapAsset'
     ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::setAlias('@wschat', __DIR__);
        $this->sourcePath = '@wschat/assets/';
        parent::init();
    }
}
