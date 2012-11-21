<?php

use \Rapid\Application\Bootstrap;

class BootstrapForTest extends Bootstrap
{
    public function initInfo()
    {
        return 'abcd';
    }
}

class BootstrapTest extends PHPUnit_Framework_TestCase
{
    protected $application;

    public function setUp()
    {
        $path = __DIR__ . '/../../../../application/';
        $this->application = new \Rapid\Application($path, 'development');
    }

    public function testCreate()
    {
        $bootstrap = new Bootstrap($this->application);
        $this->assertInstanceOf('\Rapid\Application\Bootstrap', $bootstrap);
    }

    /**
     * @depends testCreate
     */
    public function testBootstrap()
    {
        $bootstrap = new Bootstrap($this->application);
        $result = $bootstrap->bootstrap();
        $this->assertInstanceOf('\Rapid\Application\Bootstrap', $result);
    }

    /**
     * @depends testBootstrap
     */
    public function testResults()
    {
        $bootstrap = new Bootstrap($this->application);
        $this->assertEquals(array(), $bootstrap->results());
        $bootstrap->bootstrap();
        $this->assertEquals(array(), $bootstrap->results());
    }

    /**
     * @depends testResults
     */
    public function testResultsWithInitMethod()
    {
        $bootstrap = new BootstrapForTest($this->application);
        $this->assertEquals(array(), $bootstrap->results());
        $bootstrap->bootstrap();
        $this->assertEquals(array('info' => 'abcd'), $bootstrap->results());
    }
}