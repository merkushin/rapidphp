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
     * @expectedException PHPUnit_Framework_Error
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