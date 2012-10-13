<?php

use \Rapid\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $app;
    private $appPath;

    public function setUp()
    {
        $this->appPath = __DIR__ . '/';
        $this->app = new \Rapid\Application($this->appPath, 'development');
    }

    public function testCreate()
    {
        $application = $this->app;
        $this->assertNotNull($application);
        $this->assertInstanceOf('\Rapid\Application', $application);
    }

    public function testModules()
    {
        $application = $this->app;
        $this->assertEquals(array(''), $application->modules(), 'Modules should contain only default empty module');
        $application->addModule('admin/');
        $this->assertEquals(array('', 'admin/'), $application->modules(), 'Modules don\'t match');
    }

    public function testPath()
    {
        $this->assertEquals($this->appPath, $this->app->path(), 'Paths are not equal');
    }

    public function testControllers()
    {
        $application = $this->app;
        $appPath = $this->appPath;
        $this->assertEquals($appPath . 'controllers/', $application->controllerPath(), 'Controllers paths are not equal');
        $application->setControllerPath('my_controllers/');
        $this->assertEquals($appPath . 'my_controllers/', $application->controllerPath(), 'Modified controllers paths are not equal');
    }

    public function testModels()
    {
        $application = $this->app;
        $appPath = $this->appPath;
        $this->assertEquals($appPath . 'models/', $application->modelPath(), 'Models paths are not equal');
        $application->setModelPath('my_models/');
        $this->assertEquals($application->modelPath(), $appPath . 'my_models/', 'Modified models paths are not equal');
    }
}