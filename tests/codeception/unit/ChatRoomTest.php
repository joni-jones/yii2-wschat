<?php
namespace tests\codeception\unit;

use yii\codeception\TestCase;
use jones\wschat\components\User;
use jones\wschat\components\ChatRoom;


class ChatRoomTest extends TestCase
{
    public function testAddUser()
    {
        $chat = new ChatRoom();
        $user = new User();
        $chat->addUser($user);
        $this->assertEquals(1, sizeof($chat->getUsers()),
            'Chat should contain only one user');
    }

    public function testRemoveUser()
    {
        $chat = new ChatRoom();
        $user = new User(1);
        $chat->addUser($user);
        $chat->addUser(new User(2));
        $chat->addUser(new User(3));
        $chat->removeUser($user);
        $this->assertEquals(2, sizeof($chat->getUsers()),
            'Chat should contain 2 users');
        $this->assertNotContains($user, $chat->getUsers(),
            'User should be removed from chat');
    }
}