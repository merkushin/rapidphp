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
        $this->assertEquals($request->uri(), 'blog');
    }

    public function testModule()
    {
        $this->assertEquals($this->request->module(), null);
        $this->request->setModule('admin');
        $this->assertEquals($this->request->module(), 'admin');
    }

    public function testController()
    {
        $this->assertEquals($this->request->controller(), null);
        $this->request->setController('my');
        $this->assertEquals($this->request->controller(), 'my');
    }

    public function testAction()
    {
        $this->assertEquals($this->request->action(), null);
        $this->request->setAction('new');
        $this->assertEquals($this->request->action(), 'new');
    }

    public function testParams()
    {
        $request = $this->request;
        $this->assertEquals($request->params(), array());
        $request->setParam('abc', 1);
        $this->assertEquals($request->params(), array('abc' => 1));
        $this->assertEquals($request->param('abc'), 1);
    }
}