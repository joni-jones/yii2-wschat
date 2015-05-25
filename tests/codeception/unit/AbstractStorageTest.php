<?php
namespace tests\codeception\unit;

use yii\codeception\TestCase;
use jones\wschat\components\AbstractStorage;

class AbstractStorageTest extends TestCase
{
    /**
     * @covers \jones\wschat\components\AbstractStorage::factory
     */
    public function testMongoStorage()
    {
        $storage = AbstractStorage::factory('mongodb');
        $this->assertInstanceOf('\jones\wschat\collections\History', $storage);
        $storage = AbstractStorage::factory();
        $this->assertInstanceOf('\jones\wschat\collections\History', $storage);
    }

    /**
     * @covers \jones\wschat\components\AbstractStorage::factory
     */
    public function testPgsqlStorage()
    {
        $storage = AbstractStorage::factory('pgsql');
        $this->assertInstanceOf('\jones\wschat\components\DbStorage', $storage);
    }

    /**
     * @covers \jones\wschat\components\AbstractStorage::factory
     */
    public function testMysqlStorage()
    {
        $storage = AbstractStorage::factory('mysql');
        $this->assertInstanceOf('\jones\wschat\components\DbStorage', $storage);
    }
}