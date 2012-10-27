<?php

use \Rapid\Dispatcher;

class DispatcherText extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rapid\Dispatcher
     */
    private $disp;

    public function setUp()
    {
        $request = new \Rapid\Request();
        $request->setController('index');
        $request->setAction('index');

        $app = new \Rapid\Application(__DIR__ . '/../../../application/', 'development');
        $app->addModule('admin/');

        $this->disp = new \Rapid\Dispatcher($app, $request);

    }

    public function testCreate()
    {
        $this->assertInstanceOf('\Rapid\Dispatcher', $this->disp);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('\Rapid\Request', $this->disp->request());
    }

    public function testDispatch()
    {
        ob_start();
        $this->disp->dispatch();
        $output = ob_get_clean();
        $expected = '<title>RapidPHP</title>
<p>It\'s very simple web-framework!</p>';
        $this->assertEquals($expected, $output, 'Output is wrong');
    }
}