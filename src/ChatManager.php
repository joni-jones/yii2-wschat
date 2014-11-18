<?php
namespace WSChat;

class ChatManager
{
    /** @var User[] */
    private $users = [];

    public function addUser($rid)
    {
        $user = new User();
        $user->setRid($rid);
        $this->users[$rid] = $user;
    }

    public function getUserChart($rid)
    {
        $user = $this->getUserByRid($rid);
        $chat = $user->getChat();
        if (!$chat) {
            echo 'new chat for '.$rid.PHP_EOL;
            $chat = new UserChat();
            $user->setChat($chat);
        }
        return $chat;
    }

    public function getUserByRid($rid)
    {
        return $this->users[$rid];
    }

    public function removeUserFromChat($rid)
    {
        $user = $this->getUserByRid($rid);
        $chat = $user->getChat();
        if ($chat) {
            $chat->removeUser($user);
        }
        unset($this->users[$rid]);
    }

    /**
     * @TODO Need to remove all current chats and restore chats from some data
     */
    public function refreshChats()
    {

    }
}
 