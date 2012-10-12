<?php

use \Rapid\Router;

class RouterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rapid\Router
     */
    private $router;
    /**
     * @var \Rapid\Request
     */
    private $request;
    /**
     * @var \Rapid\Application
     */
    private $application;

    public function setUp()
    {
        $_SERVER = array(
            'REQUEST_URI' => '/',
        );
        $this->request = new \Rapid\Request();

        $this->application = new \Rapid\Application(__DIR__ . '/../../../application/', 'development');
        $this->application->addModule('index/');

        $this->router = new \Rapid\Router($this->application, $this->request);
    }

    public function testCreate()
    {
        $this->assertInstanceOf('\Rapid\Router', $this->router);
    }

    public function testRoute()
    {
        $request = $this->router->route();
        $this->assertInstanceOf('\Rapid\Request', $request);
        $this->assertEquals($request->action(), 'index');
        $this->assertEquals($request->controller(), 'index');
        $this->assertEquals($request->module(), '');
    }
}