<?php
namespace WSChat;

class UserChat
{
    /** @var User[] */
    private $users = [];

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user)
    {
        $this->users[$user->getId()] = $user;
    }

    public function removeUser($user)
    {
        unset($this->users[$user->getId()]);
    }
}
 