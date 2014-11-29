<?php
namespace jones\wschat;

use yii\web\AssetBundle;

/**
 * Class ChatAsset
 * @package jones\wschat
 */
class ChatAsset extends AssetBundle
{
    public $css = [
        'css/bootstrap.min.css',
        'css/style.css'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__.'/assets/';
        parent::init();
    }
}
