<?php
namespace WSChat;

/**
 * Class User
 * @package WSChat
 */
class User
{
    public $id;
    private $rid;
    /** @var ChatRoom */
    private $chat;

    public function __construct()
    {
        $this->id = uniqid();
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
}
 