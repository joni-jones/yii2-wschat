<?php
namespace WSChat;

/**
 * Class ChatManager
 * @package WSChat
 */
class ChatManager
{
    /** @var User[] */
    private $users = [];

    /**
     * Add new user to manager
     *
     * @access public
     * @param $rid
     * @return void
     */
    public function addUser($rid)
    {
        $user = new User();
        $user->setRid($rid);
        $this->users[$rid] = $user;
    }

    /**
     * Return if exists user chat room
     *
     * @access public
     * @param $rid
     * @return null|ChatRoom
     */
    public function getUserChat($rid)
    {
        $user = $this->getUserByRid($rid);
        return $user ? $user->getChat() : null;
    }

    /**
     * Find chat room by id, if not exists create new chat room
     * and assign to user by resource id
     *
     * @access public
     * @param $chatId
     * @param $rid
     * @return null|ChatRoom
     */
    public function findChat($chatId, $rid)
    {
        $chat = null;
        $storedUser = $this->getUserByRid($rid);
        foreach ($this->users as $user) {
            $userChat = $user->getChat();
            if (!$userChat) {
                continue;
            }
            if ($userChat->getUid() == $chatId) {
                $chat = $userChat;
                echo 'User('.$rid.') will be joined to '.$chatId.PHP_EOL;
                break;
            }
        }
        if (!$chat) {
            echo 'Create new chat room: '.$chatId.' for user('.$rid.')'.PHP_EOL;
            $chat = new ChatRoom();
            $chat->setUid($chatId);
        }
        $storedUser->setChat($chat);
        return $chat;
    }

    /**
     * Get user by resource id
     *
     * @access public
     * @param $rid
     * @return User
     */
    public function getUserByRid($rid)
    {
        return $this->users[$rid];
    }

    /**
     * Find user by resource id and remove it from chat
     *
     * @access public
     * @param $rid
     * @return void
     */
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

    /**
     * @TODO in future user attributes must be set from server side instead client side
     */
    public function setUserAttributes($rid, array $attrs)
    {
        $user = $this->getUserByRid($rid);
        foreach ($attrs as $key => $value) {
            $user->$key = $value;
        }
        $this->users[$rid] = $user;
    }
}
 