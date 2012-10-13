<?php

use \Rapid\Controller;

class ControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rapid\Controller
     */
    private $controller;

    public function setUp()
    {
        $request = new \Rapid\Request();
        $request->setController('index');
        $request->setAction('index');

        $app = new \Rapid\Application(__DIR__ . '/../../../application/', 'development');
        $app->addModule('admin/');

        $dispatcher = new \Rapid\Dispatcher($app, $request);
        $this->controller = new \Rapid\Controller($dispatcher, $request);

    }

    public function testCreate()
    {
        $this->assertInstanceOf('\Rapid\Controller', $this->controller);
    }

    /**
     * @expectedException \Rapid\Controller\Exception
     */
    public function testName()
    {
        $this->controller->name();
    }

    public function testFullName()
    {
        $this->assertEquals('Controller', $this->controller->fullName(), 'Controller full name is incorrect');
    }
}