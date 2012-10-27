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
            'REQUEST_URI' => '/index/index/param1/1/?param2=2',
        );
        $this->request = new \Rapid\Request();

        $this->application = new \Rapid\Application(__DIR__ . '/../../../application/', 'development');
        $this->application->addModule('admin/');

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
        $this->assertEquals('index', $request->action(), 'Incorrect Action');
        $this->assertEquals('index', $request->controller(), 'Incorrect Controller');
        $this->assertEquals('', $request->module(), 'Incorrect Module');
    }

    /**
     * @depends testRoute
     */
    public function testParams()
    {
        $request = $this->router->route();
        $this->assertEquals(array('param1' => 1, 'param2' => 2), $request->params());
    }

    public function testCustomRouteWithQueryString()
    {
        $_SERVER = array(
            'REQUEST_URI' => '/my-address/?param1=2',
        );
        $request = new \Rapid\Request();

        $application = new \Rapid\Application(__DIR__ . '/../../../application/', 'development');

        $router = new \Rapid\Router($application, $request);
        $router->setCustomRoutes(array(new \Rapid\Router\Route('my-address', 'index', 'index')));

        $request = $router->route();
        $this->assertInstanceOf('\Rapid\Request', $request);
        $this->assertEquals('index', $request->action(), 'Incorrect Action');
        $this->assertEquals('index', $request->controller(), 'Incorrect Controller');
        $this->assertEquals('', $request->module(), 'Incorrect Module');
        $this->assertEquals(2, $request->param('param1'));
    }
}