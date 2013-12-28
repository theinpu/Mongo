<?php
/**
 * User: inpu
 * Date: 28.12.13
 * Time: 7:40
 */

namespace aascms\tests;

use aascms\Mongo\Mongo;

class MongoTest extends \PHPUnit_Framework_TestCase {

    public function testMongo()
    {
        $collection = Mongo::getCollection("test");
        $this->assertInstanceOf('\MongoCollection', $collection);
        $testItem = array('test' => 'test');
        $this->assertFalse(!$collection->save($testItem));
        $savedItem = $collection->findOne();
        $this->assertEquals($testItem, $savedItem);
        $this->assertFalse(!$collection->drop());
    }

    public function testGetDb()
    {
        $db = Mongo::getDb();
        $this->assertInstanceOf('\MongoDB', $db);
        $namedDb = Mongo::getDb('local');
        $this->assertInstanceOf('\MongoDB', $namedDb);
    }
}
 