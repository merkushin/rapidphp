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
        $this->assertEquals($application->modules(), array(''));
        $application->addModule('admin/');
        $this->assertEquals($application->modules(), array('', 'admin/'));
    }

    public function testPath()
    {
        $this->assertEquals($this->app->path(), $this->appPath);
    }

    public function testControllers()
    {
        $application = $this->app;
        $appPath = $this->appPath;
        $this->assertEquals($application->controllerPath(), $appPath . 'controllers/');
        $application->setControllerPath('my_controllers/');
        $this->assertEquals($application->controllerPath(), $appPath . 'my_controllers/');
    }

    public function testModels()
    {
        $application = $this->app;
        $appPath = $this->appPath;
        $this->assertEquals($application->modelPath(), $appPath . 'models/');
        $application->setModelPath('my_models/');
        $this->assertEquals($application->modelPath(), $appPath . 'my_models/');
    }
}