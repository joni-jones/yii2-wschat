<?php
namespace jones\wschat;

use Yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Json;

/**
 * Class ChatWidget
 * @package jones\wschat
 */
class ChatWidget extends Widget
{
    /**
     * @var boolean set to true if widget will be run for auth users
     */
    public $auth = false;
    public $user_id = null;
    public $view = 'index';
    /** @var integer $port web socket port */
    public $port = 8080;
    /** @var array $chatList list of preloaded chats */
    public $chatList = [
        'id' => 1,
        'title' => 'All'
    ];
    /** @var string path to avatars folder */
    public $imgPath = '@vendor/joni-jones/yii2-wschat/assets/img';

    /**
     * @var boolean is user available to add nwe rooms
     */
    public $add_room = true;

    /**
     * @override
     */
    public function run()
    {
        $this->registerJsOptions();
        Yii::$app->assetManager->publish($this->imgPath);
        return $this->render($this->view, [
            'auth' => $this->auth,
            'add_room' => $this->add_room
        ]);
    }

    /**
     * Register js variables
     *
     * @access protected
     * @return void
     */
    protected function registerJsOptions()
    {
        $opts = [
            'var currentUserId = '.($this->user_id ?: 0).';',
            'var port = '.$this->port.';',
            'var chatList = '.Json::encode($this->chatList).';',
            'var imgPath = "'.Yii::$app->assetManager->getPublishedUrl($this->imgPath).'";',
        ];
        $this->getView()->registerJs(implode(' ', $opts), View::POS_BEGIN);
    }
}
 
