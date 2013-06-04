<?php

use \Rapid\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {
        $_SERVER = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/blog/',
        );

        $this->request = new \Rapid\Request();
    }

    public function testCreate()
    {
        $request = $this->request;

        $this->assertInstanceOf('Rapid\Request\Container', $request->post());
        $this->assertEquals($request->post()->toArray(), $_POST);

        $this->assertInstanceOf('Rapid\Request\Container', $request->get());
        $this->assertEquals($request->get()->toArray(), $_GET);

        $this->assertInstanceOf('Rapid\Request\Container', $request->request());
        $this->assertEquals($request->request()->toArray(), $_REQUEST);

        $this->assertInstanceOf('Rapid\Request\Container', $request->cookie());
        $this->assertEquals($request->cookie()->toArray(), $_COOKIE);

        $this->assertInstanceOf('Rapid\Request\Container', $request->files());
        $this->assertEquals($request->files()->toArray(), $_FILES);

        $this->assertInstanceOf('Rapid\Request\Container', $request->server());
        $this->assertEquals($request->server()->toArray(), $_SERVER);
    }

    public function testIsNotPost()
    {
        $this->assertEquals($this->request->isPost(), false);
    }

    public function testIsPost()
    {
        $_SERVER = array(
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/blog/',
        );

        $request = new \Rapid\Request();
        $this->assertEquals($request->isPost(), true);
    }

    public function testIsXmlHttpRequest()
    {
        $this->assertEquals($this->request->isXmlHttpRequest(), false);
    }

    public function testUri()
    {
        $request = $this->request;
        $this->assertEquals('blog', $request->uri(), 'Incorrect URI');
    }

    public function testModule()
    {
        $this->assertEquals(null, $this->request->module(), 'Module is not null');
        $this->request->setModule('admin');
        $this->assertEquals('admin', $this->request->module(), 'Module is not "admin"');
    }

    public function testController()
    {
        $this->assertEquals(null, $this->request->controller(), 'Controller is not null');
        $this->request->setController('my');
        $this->assertEquals('my', $this->request->controller(), 'Controller is not "my"');
    }

    public function testAction()
    {
        $this->assertEquals(null, $this->request->action(), 'Action is not null');
        $this->request->setAction('new');
        $this->assertEquals('new', $this->request->action(), 'Action is not "new"');
    }

    public function testParams()
    {
        $request = $this->request;
        $this->assertEquals(array(), $request->params(), 'Params are not empty');
        $request->setParam('abc', 1);
        $this->assertEquals(array('abc' => 1), $request->params(), 'Params are not as expected');
        $this->assertEquals(1, $request->param('abc'), 'abc does not match');
    }
}