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
}