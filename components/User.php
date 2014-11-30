<?php
namespace jones\wschat\components;

/**
 * Class User
 * @package \jones\wschat\components
 */
class User
{
    public $id;
    public $name;
    private $rid;
    /** @var \jones\wschat\components\ChatRoom */
    private $chat;

    public function __construct()
    {
        $this->id = uniqid();
        $this->name = 'name#'.rand(1, 100);
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
}
 