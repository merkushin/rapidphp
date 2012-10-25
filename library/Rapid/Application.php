<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Application
{
    /**
     * @var string
     */
    protected $applicationPath;
    /**
     * @var string
     */
    protected $environment;
    /**
     * @var string
     */
    protected $controllerDirectory;
    /**
     * @var string
     */
    protected $modelDirectory;
    /**
     * @var string
     */
    protected $viewDirectory;

    /**
     * @var array
     */
    protected $modules = array('');

    /**
     * @var array
     */
    protected $customRoutes = array();

    public function __construct($applicationPath, $environment)
    {
        $this->applicationPath = $applicationPath;
        $this->environment = $environment;
        $this->setApplicationPaths();
        $this->addApplicationToLoader();
    }

    public function run()
    {
        Session::init('rapidphp');
        $router = new \Rapid\Router($this, new \Rapid\Request());
        $router->setCustomRoutes($this->customRoutes);
        $request = $router->route();
        $dispatcher = new \Rapid\Dispatcher($this, $request);
        $dispatcher->dispatch();
    }

    protected function setApplicationPaths()
    {
        $this->setControllerPath('controllers/');
        $this->setModelPath('models/');
        $this->setViewPath('views/');
        return $this;
    }

    public function setControllerPath($path)
    {
        $this->controllerDirectory = $this->applicationPath . $path;
        return $this;
    }

    public function controllerPath()
    {
        return $this->controllerDirectory;
    }

    public function setModelPath($path)
    {
        $this->modelDirectory = $this->applicationPath . $path;
        return $this;
    }

    public function modelPath()
    {
        return $this->modelDirectory;
    }

    public function setViewPath($path)
    {
        $this->viewDirectory = $this->applicationPath . $path;
        return $this;
    }

    public function viewPath()
    {
        return $this->viewDirectory;
    }

    protected function addApplicationToLoader()
    {
        \Rapid\Loader::setApplication($this);
        return $this;
    }

    public function modules()
    {
        return $this->modules;
    }

    public function addModule($module)
    {
        $this->modules[] = $module;
        return $this;
    }

    public function hasModule($module)
    {
        $module = substr($module, -1) == '/' ? $module : $module . '/';
        foreach ($this->modules() as $m)
        {
            if ($module == $m)
            {
                return true;
            }
        }
        return false;
    }

    public function customRoutes()
    {
        return $this->customRoutes;
    }

    public function addCustomRoute(\Rapid\Router\RouteInterface $route)
    {
        $this->customRoutes[] = $route;
    }

    public function path()
    {
        return $this->applicationPath;
    }
}