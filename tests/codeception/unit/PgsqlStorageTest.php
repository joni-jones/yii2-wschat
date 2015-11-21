<?php
namespace tests\codeception\unit;

use jones\wschat\components\AbstractStorage;
use yii\codeception\TestCase;

/**
 * Class PgsqlStorageTest
 * @package tests\codeception\unit
 */
class PgsqlStorageTest extends TestCase
{
    protected $params = [
        'chat_id' => 1,
        'chat_title' => 'Pgsql chat room',
        'user_id' => 1,
        'username' => 'Pgsql user',
        'avatar_16' => null,
        'avatar_32' => null,
        'message' => 'Simple text message',
        'timestamp' => 0
    ];

    /**
     * @covers \jones\wschat\components\DbStorage::storeMessage
     */
    public function testStoreMessage()
    {
        AbstractStorage::factory('pgsql')->storeMessage($this->params);
    }

    /**
     * @depends testStoreMessage
     * @covers \jones\wschat\components\DbStorage::getHistory
     */
    public function testGetHistory()
    {
        $data = AbstractStorage::factory('pgsql')->getHistory($this->params['chat_id']);
        $this->assertNotEmpty($data);
        $this->assertEquals(1, sizeof($data), 'Only one message should be in history table');
        $this->assertEquals($this->params['message'], $data[0]['message']);
    }

}
