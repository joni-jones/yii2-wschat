<?php

namespace tests\codeception\unit;

use yii\codeception\TestCase;
use jones\wschat\components\ChatManager;

class ChatTest extends TestCase
{
   /** @var \UnitTester */
    protected $tester;
    /** @var \jones\wschat\components\ChatManager */
    protected $cm;
    protected $rid = 1;
    protected $userId = 1;
    protected $chatId = 1;

    protected function _before()
    {
        $this->cm = new ChatManager();
    }

    protected function _after()
    {
        unset($this->cm);
    }

    public function testAddUser()
    {
        $this->cm->addUser($this->rid, $this->userId);
        $user = $this->cm->getUserByRid($this->rid);
        $this->assertInstanceOf('jones\wschat\components\User', $user,
            'User should be instance of jones\wschat\components\User');
        $this->assertEquals($this->userId, $user->getId(), 'User id should match');
    }

    /**
     * @depends testAddUser
     */
    public function testFindChat()
    {
        $this->cm->addUser($this->rid, $this->userId);
        $chat = $this->cm->findChat($this->chatId, $this->rid);
        $this->assertInstanceOf('jones\wschat\components\ChatRoom', $chat,
            'Chat should be instance of jones\wschat\ChatRoom');
        $this->assertEquals($this->chatId, $chat->getUid(), 'Chat id should match');
        $users = $chat->getUsers();
        $this->assertEquals(1, sizeof($users), 'We added only one user');
        $rid = rand(1, 100);
        $this->cm->addUser($rid, 2);
        $chat = $this->cm->findChat($this->chatId, $rid);
        $this->assertEquals(2, sizeof($chat->getUsers()), 'We added second user');
    }

    public function testUsersExistsInChat()
    {
        $this->cm->addUser($this->rid, $this->userId);
        $this->assertFalse((boolean)$this->cm->isUserExistsInChat($this->userId, $this->chatId),
            'User should not be added to chat');
        $this->cm->findChat($this->chatId, $this->rid);
        $rid = $this->cm->isUserExistsInChat($this->userId, $this->chatId);
        $this->assertTrue((boolean)$rid, 'User should be in chat');
        $this->assertEquals($this->rid, $rid, 'Resource id should match');
    }

    public function testRemoveUserFromChat()
    {
        $this->cm->addUser($this->rid, $this->userId);
        $chat = $this->cm->findChat($this->chatId, $this->rid);
        $this->cm->removeUserFromChat($this->rid);
        $this->assertEquals(0, sizeof($chat->getUsers()), 'Chat should not contains any users');
    }
}