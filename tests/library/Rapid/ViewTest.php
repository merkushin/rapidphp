<?php

use \Rapid\View;

class ViewTest extends PHPUnit_Framework_TestCase
{
    protected $path;
    protected $expectedHtml;

    public function setUp()
    {
        $this->path = __DIR__ . '/../../../application/views/index/index.phtml';
        $this->expectedHtml = <<<EOD
<title>RapidPHP</title>
<p>It's very simple web-framework!</p>
EOD;
    }

    public function testCreate()
    {
        $view = new \Rapid\View();
        $this->assertInstanceOf('\Rapid\View', $view);
    }

    public function testPath()
    {
        $view = new \Rapid\View();
        $view->setFile($this->path);
        $this->assertEquals($this->path, $view->file(), 'File paths don\'t match');
    }

    public function testVariables()
    {
        $view = new \Rapid\View();

        $this->assertEquals(array(), $view->variables(), 'Variables are not empty');

        $view->setVariable('abc', 1);
        $this->assertEquals(1, $view->variable('abc'), 'Method View::variable() returns incorrect value');
        $this->assertEquals(1, $view->abc, 'Magic method returns incorrect value');

        $vars = array('abcc' => 2, 'dsa' => 3);
        $view->setVariables($vars);
        $this->assertEquals(array_merge($vars, array('abc' => 1)), $view->variables(), 'Variables contain incorrect values');
    }

    public function testRender()
    {
        $view = new \Rapid\View();
        $view->setFile($this->path);
        $view->var = 'It\'s very simple web-framework!';

        $this->assertEquals($this->expectedHtml, $view->render(), 'Render returns incorrect HTML');
    }

    public function testDisplay()
    {
        $view = new \Rapid\View();
        $view->setFile($this->path);
        $view->var = 'It\'s very simple web-framework!';

        $this->expectOutputString($this->expectedHtml);
        $view->display();
    }
}