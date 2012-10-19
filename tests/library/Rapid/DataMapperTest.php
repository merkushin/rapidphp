<?php

use \Rapid\DataMapper;

class DataMapperTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $mapper = new \Rapid\DataMapper();
        $this->assertInstanceOf('\Rapid\DataMapper', $mapper);
    }

    public function testTablename()
    {
        $mapper = new DataMapper();
        $mapper->setTablename('table');
        $this->assertEquals('table', $mapper->tablename());
    }
}