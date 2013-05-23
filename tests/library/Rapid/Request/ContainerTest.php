<?php

class ContainerTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $container = new \Rapid\Request\Container(array());
        $this->assertInstanceOf('Rapid\Request\Container', $container);
    }

    public function testGetValue()
    {
        $data = array(
            'key' => 'value',
        );
        $container = new \Rapid\Request\Container($data);

        $this->assertEquals($data['key'], $container->get('key'));
    }

    public function testDefault()
    {
        $container = new \Rapid\Request\Container(array());

        $this->assertNull($container->get('key'));

        $this->assertEquals('default', $container->get('key', 'default'));
    }

    public function testArrayAccess()
    {
        $data = array(
            'key1' => 'value1',
            'key2' => 'value2',
        );
        $container = new \Rapid\Request\Container($data);

        $this->assertEquals('value1', $container['key1']);
        $this->assertEquals('value2', $container['key2']);
        $this->assertNull($container['key3']);
    }

    public function testToArray()
    {
        $data = array(
            'key1' => 'value1',
            'key2' => 'value2',
        );
        $container = new \Rapid\Request\Container($data);

        $this->assertEquals($data, $container->toArray());
    }
}