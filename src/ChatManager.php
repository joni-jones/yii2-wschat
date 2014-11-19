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
    public function getUserChatRoom($rid)
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
        $user = $this->getUserByRid($rid);
        foreach ($this->users as $user) {
            $userChat = $user->getChat();
            if (!$userChat) {
                continue;
            }
            if ($userChat->getUid() == $chatId) {
                $chat = $userChat;
                break;
            }
        }
        if (!$chat) {
            $chat = new ChatRoom();
            $chat->setUid($chatId);
            $user->setChat($chat);
        }
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
}
 