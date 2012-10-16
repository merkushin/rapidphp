<?php

use \Rapid\Loader;

class LoaderTest extends PHPUnit_Framework_TestCase
{
    private $application;

    public function setUp()
    {
        $this->application = new \Rapid\Application(__DIR__ . '/../../../application/', 'development');
        spl_autoload_unregister(array('\Rapid\Loader', 'basicLoader'));
    }

    public function testNewApp()
    {
        $this->assertInstanceOf('\Rapid\Application', $this->application);
    }

    /**
     * @expectedException \Rapid\Loader\Exception
     */
    public function testErrorLoadLibrary()
    {
        \Rapid\Loader::loadLibrary('Rapid\Unknown');
    }

    public function testSuccessLoadLibrary()
    {
        \Rapid\Loader::loadLibrary('Rapid\Version');

        $version = new \Rapid\Version();
        $this->assertInstanceOf('\Rapid\Version', $version);
        $this->assertEquals('0.12.0', \Rapid\Version::get());

        \Rapid\Loader::loadLibrary('Rapid\Controller');
        $controller = new \Rapid\Controller(null, null);
        $this->assertInstanceOf('\Rapid\Controller', $controller);
    }

    /**
     * @depends testSuccessLoadLibrary
     * @expectedException \Rapid\Loader\Exception
     */
    public function testErrorLoadController()
    {
        \Rapid\Loader::loadLibrary('Rapid\Loader\Exception');
        \Rapid\Loader::loadController('Controllers\MyController');
    }

    public function testSuccessLoadController()
    {
        \Rapid\Loader::loadController('Controllers\IndexController');
    }

    public function tearDown()
    {
        spl_autoload_register(array('\Rapid\Loader', 'basicLoader'));
    }

}