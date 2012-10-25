<?php

namespace Rapid\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testPass1()
    {
        $route = new \Rapid\Router\Route('abc/:controller/:action/:gogo');

        $_SERVER['REQUEST_URI'] = 'abc/controller1/action1/gogo1';
        $request = new \Rapid\Request();
        $route->pass($request);

        $this->assertEquals('action1', $request->action());
        $this->assertEquals('controller1', $request->controller());
        $this->assertEquals('', $request->module());
    }

    public function testPass2()
    {
        $route = new \Rapid\Router\Route(':module/:controller/:action/:gogo');

        $_SERVER['REQUEST_URI'] = 'abc/controller1/action1/gogo1';
        $request = new \Rapid\Request();
        $route->pass($request);

        $this->assertEquals('action1', $request->action());
        $this->assertEquals('controller1', $request->controller());
        $this->assertEquals('abc/', $request->module());
    }
}