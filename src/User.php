<?php
namespace WSChat;

/**
 * Class User
 * @package WSChat
 */
class User
{
    public $id;
    public $name;
    public $avatar_16;
    public $avatar_32;
    private $rid;
    /** @var ChatRoom */
    private $chat;

    public function __construct()
    {
        $this->id = uniqid();
        /**
         * @TODO need to load user with real name
         */
        $num = rand(1, 100);
        $this->name = 'user #'.$num;
        $this->setAvatar($num);
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
     * @return ChatRoom
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Set chat room for user
     *
     * @access public
     * @param ChatRoom $chat
     * @return void
     */
    public function setChat(ChatRoom $chat)
    {
        $this->chat = $chat;
        $this->chat->addUser($this);
    }

    protected function setAvatar($num)
    {
        $image_32 = 'uploads/avatar_'.$num.'_32.png';
        $image_16 = 'uploads/avatar_'.$num.'_16.png';
        if (!file_exists(__DIR__.'/../'.$image_32)) {
            $file = file_get_contents('http://www.gravatar.com/avatar/'.$num.'?s=32&d=identicon&r=g');
            file_put_contents(__DIR__.'/../'.$image_32, $file);
            $file = file_get_contents('http://www.gravatar.com/avatar/'.$num.'?s=16&d=identicon&r=g');
            file_put_contents(__DIR__.'/../'.$image_16, $file);
        }
        $this->avatar_32 = $image_32;
        $this->avatar_16 = $image_16;
    }
}
 