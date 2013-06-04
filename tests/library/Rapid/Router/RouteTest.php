<?php

namespace Rapid\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testPass()
    {
        $route = new \Rapid\Router\Route('abc/:controller/:action/:gogo');

        $_SERVER['REQUEST_URI'] = 'abc/controller1/action1/gogo1';
        $request = new \Rapid\Request();
        $route->pass($request);

        $this->assertEquals('action1', $request->action());
        $this->assertEquals('controller1', $request->controller());
        $this->assertEquals('', $request->module());
    }

    public function testPassWithModule()
    {
        $route = new \Rapid\Router\Route(':module/:controller/:action/:gogo');

        $_SERVER['REQUEST_URI'] = 'abc/controller1/action1/gogo1';
        $request = new \Rapid\Request();
        $route->pass($request);

        $this->assertEquals('action1', $request->action());
        $this->assertEquals('controller1', $request->controller());
        $this->assertEquals('abc/', $request->module());
    }

    public function testBugParseRouteWithoutRegexStartAndEnd()
    {
        $route = new \Rapid\Router\Route(':controller');

        $_SERVER['REQUEST_URI'] = 'abc/controller1/action1/gogo1';
        $request = new \Rapid\Request();
        $route->pass($request);

        $this->assertEquals(null, $request->action());
        $this->assertEquals(null, $request->controller());
        $this->assertEquals(null, $request->module());
    }

    public function testBugRouteWithQueryString()
    {
        $route = new \Rapid\Router\Route(':module/:controller/:action/:gogo');

        $_SERVER['REQUEST_URI'] = 'abc/controller1/action1/gogo1?param=1';
        $request = new \Rapid\Request();
        $route->pass($request);

        $this->assertEquals('action1', $request->action());
        $this->assertEquals('controller1', $request->controller());
        $this->assertEquals('abc/', $request->module());
        $this->assertEquals('gogo1', $request->param('gogo'));
    }
}