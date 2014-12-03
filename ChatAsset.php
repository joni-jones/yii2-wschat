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
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.css',
        'css/style.css'
    ];

    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js',
        '//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.2/backbone-min.js',
        '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/pnotify/2.0.0/pnotify.core.min.js',
        'js/helper.js',
        'js/chat.js',
        'js/models/user.js',
        'js/models/room.js',
        'js/chat-room.js',
        'js/collections/users.js',
        'js/collections/rooms.js',
        'js/views/message.js',
        'js/views/chat.js',
        'js/views/room.js',
        'js/views/rooms.js',
        'js/views/user.js',
        'js/views/add_user.js',
        'js/views/users.js',
        'js/main.js',
    ];

     public $depends = [
         '\yii\bootstrap\BootstrapPluginAsset'
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
