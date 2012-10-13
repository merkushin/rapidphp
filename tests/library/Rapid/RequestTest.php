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

        $this->assertEquals($request->post(), $_POST);
        $this->assertEquals($request->get(), $_GET);
        $this->assertEquals($request->request(), $_REQUEST);
        $this->assertEquals($request->cookie(), $_COOKIE);
        $this->assertEquals($request->files(), $_FILES);
        $this->assertEquals($request->server(), $_SERVER);
    }

    public function testIsPost()
    {
        $this->assertEquals($this->request->isPost(), false);
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