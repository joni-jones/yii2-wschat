<?php

namespace tests\codeception\unit;

use Yii;
use yii\codeception\TestCase;
use jones\wschat\components\ChatManager;

class ChatTest extends TestCase
{
   /** @var \UnitTester */
    protected $tester;
    /** @var \jones\wschat\components\ChatManager */
    protected $cm;
    protected $rid = 1;
    protected $userId;
    protected $chatId = 1;

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        $this->cm = new ChatManager();
        $this->chatId = 1;
        $this->userId = uniqid();
    }

    public function testAddUser()
    {
        $this->cm->addUser($this->rid, $this->userId);
        $user = $this->cm->getUserByRid($this->rid);
        $this->assertInstanceOf('jones\wschat\components\User', $user,
            'User should be instance of jones\wschat\components\User');
        $this->assertEquals($this->userId, $user->getId(), 'User id should match');
    }
}