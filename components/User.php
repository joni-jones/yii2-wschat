<?php
namespace jones\wschat\components;

use Yii;

/**
 * Class User
 * @package \jones\wschat\components
 */
class User
{
    public $id;
    public $name;
    public $avatar_16;
    public $avatar_32;
    private $rid;
    /** @var \jones\wschat\components\ChatRoom */
    private $chat;

    public function __construct()
    {
        /**
         * @TODO set id from real instance
         */
        $this->id = uniqid();
        $this->setAvatar();
    }

    /**
     * Get user id
     *
     * @param string
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user resource id
     *
     * @access public
     * @return string
     */
    public function getRid()
    {
        return $this->rid;
    }

    /**
     * Set user resource id
     *
     * @access public
     * @param $rid
     * @return void
     */
    public function setRid($rid)
    {
        $this->rid = $rid;
    }

    /**
     * Get user chat room
     *
     * @access public
     * @return \jones\wschat\components\ChatRoom
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Set chat room for user
     *
     * @access public
     * @param \jones\wschat\components\ChatRoom $chat
     * @return void
     */
    public function setChat(ChatRoom $chat)
    {
        $this->chat = $chat;
        $this->chat->addUser($this);
    }

    /**
     * @TODO add real setter
     */
    protected function setAvatar()
    {
        $num = rand(1, 100);
        $this->name = 'name #'.$num;
        $dirPath = Yii::$app->basePath.'/web';
        $image_32 = '/uploads/avatar_'.$num.'_32.png';
        $image_16 = '/uploads/avatar_'.$num.'_16.png';
        if (!file_exists($dirPath.$image_32)) {
            if (!is_dir($dirPath.'/uploads')) {
                mkdir($dirPath.'/uploads');
            }
            $file = file_get_contents('http://www.gravatar.com/avatar/'.$num.'?s=32&d=identicon&r=g');
            file_put_contents($dirPath.$image_32, $file);
            $file = file_get_contents('http://www.gravatar.com/avatar/'.$num.'?s=16&d=identicon&r=g');
            file_put_contents($dirPath.$image_16, $file);
        }
        $this->avatar_32 = $image_32;
        $this->avatar_16 = $image_16;
    }
}
 