<?php
namespace jones\wschat\components;

use Yii;
use jones\wschat\collections\History;

/**
 * Class ChatManager
 * @package \jones\wschat\components
 */
class ChatManager
{
    /** @var \jones\wschat\components\User[] */
    private $users = [];
    /** @var string a namespace of class to get user instance */
    public $userClassName = null;

    /**
     * Check if user exists in list
     * return resource id if user in current chat - else null
     *
     * @access private
     * @param $id
     * @param $chatId
     * @return null|int
     */
    public function isUserExistsInChat($id, $chatId)
    {
        foreach ($this->users as $rid => $user) {
            $chat = $user->getChat();
            if (!$chat) {
                continue;
            }
            if ($user->id == $id && $chat->getUid() == $chatId) {
                return $rid;
            }
        }
        return null;
    }

    /**
     * Add new user to manager
     *
     * @access public
     * @param integer $rid
     * @param mixed $id
     * @param array $props
     * @return void
     */
    public function addUser($rid, $id, array $props = [])
    {
        $user = new User($id, $this->userClassName, $props);
        $user->setRid($rid);
        $this->users[$rid] = $user;
    }

    /**
     * Return if exists user chat room
     *
     * @access public
     * @param $rid
     * @return \jones\wschat\components\ChatRoom|null
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
     * @return \jones\wschat\components\ChatRoom|null
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
                Yii::info('User('.$user->id.') will be joined to: '.$chatId, 'chat');
                break;
            }
        }
        if (!$chat) {
            Yii::info('New chat room: '.$chatId.' for user: '.$storedUser->id, 'chat');
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
        return !empty($this->users[$rid]) ? $this->users[$rid] : null;
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
        if (!$user) {
            return;
        }
        $chat = $user->getChat();
        if ($chat) {
            $chat->removeUser($user);
        }
        unset($this->users[$rid]);
    }

    /**
     * Store chat message
     *
     * @access public
     * @param \jones\wschat\components\User $user
     * @param \jones\wschat\components\ChatRoom $chat
     * @param string $message
     */
    public function storeMessage(User $user, ChatRoom $chat, $message)
    {
        $params = [
            'chat_id' => $chat->getUid(),
            'chat_title' => $chat->title,
            'user_id' => $user->getId(),
            'username' => $user->username,
            'avatar_16' => $user->avatar_16,
            'avatar_32' => $user->avatar_32,
            'message' => $message['message'],
            'timestamp' => $message['timestamp']
        ];
        AbstractStorage::factory()->storeMessage($params);
    }

    /**
     * Load user chat history
     *
     * @access public
     * @param mixed $chatId
     * @param integer $limit
     * @return array
     */
    public function getHistory($chatId, $limit = 10)
    {
        return AbstractStorage::factory()->getHistory($chatId, $limit);
    }
}
 