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
        'css/style.css'
    ];

    public $js = [
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
        'js/views/add_room.js',
        'js/views/rooms.js',
        'js/views/user.js',
        'js/views/add_user.js',
        'js/views/users.js',
        'js/main.js',
    ];

     public $depends = [
         'jones\wschat\ChatLibAsset'
     ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__.'/assets/';
        //set minimized version of js scripts for non debug version
        if (!YII_DEBUG) {
            $this->js = ['js/chat.min.js'];
        }
        parent::init();
    }
}
