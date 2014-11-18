<?php

namespace WSChat;

class User
{
    private $id;
    private $rid;
    /** @var UserChat */
    private $chat;

    public function __construct()
    {
        $this->id = uniqid();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRid()
    {
        return $this->rid;
    }

    public function setRid($rid)
    {
        $this->rid = $rid;
    }

    public function getChat()
    {
        return $this->chat;
    }

    public function setChat(UserChat $chat)
    {
        $this->chat = $chat;
        $this->chat->addUser($this);
    }
}
 