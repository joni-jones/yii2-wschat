<?php
namespace jones\wschat\components;

/**
 * Class ChatRoom
 * @package \jones\wschat\components
 */
class ChatRoom
{
    /** @var string */
    private $uid;
    /** @var \jones\wschat\components\User[] */
    private $users = [];

    /**
     * Set chat room unique id
     *
     * @access public
     * @param $uid
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Get chat room unique id
     *
     * @access public
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Get chat room user list
     *
     * @access public
     * @return \jones\wschat\components\User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user to chat room
     *
     * @access public
     * @param \jones\wschat\components\User $user
     * @return void
     */
    public function addUser(User $user)
    {
        $this->users[$user->getId()] = $user;
    }

    /**
     * Remove user from chat room
     *
     * @access public
     * @param \jones\wschat\components\User $user
     * @return void
     */
    public function removeUser(User $user)
    {
        unset($this->users[$user->getId()]);
    }
}
 