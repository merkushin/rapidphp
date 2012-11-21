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
     * @var \Rapid\Config
     */
    protected $config;

    /**
     * @var \Rapid\Application\Bootstrap
     */
    protected $bootstrap;

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
     * @var string
     */
    protected $layoutDirectory;

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
        $this->setBootstrap();
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

    public function applicationPath()
    {
        return $this->applicationPath;
    }

    protected function setApplicationPaths()
    {
        $this->setControllerPath('controllers/');
        $this->setModelPath('models/');
        $this->setViewPath('views/');
        $this->setLayoutPath('layouts/');
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

    public function setLayoutPath($path)
    {
        $this->layoutDirectory = $this->viewDirectory . $path;
    }

    public function layoutPath()
    {
        return $this->layoutDirectory;
    }

    protected function addApplicationToLoader()
    {
        \Rapid\Loader::setApplication($this);
        return $this;
    }

    protected function setBootstrap()
    {
        $bootstrapPath = $this->applicationPath() . 'Bootstrap.php';
        if (file_exists($bootstrapPath))
        {
            include $bootstrapPath;
            $this->bootstrap = new \Bootstrap($this);
        }
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

    public function setConfig($config)
    {
        if (!($config instanceof \Rapid\Config))
        {
            $config = new \Rapid\Config($config);
        }

        if ($this->config && $this->config instanceof \Rapid\Config)
        {
            $this->config->merge($config);
        }
        else
        {
            $this->config = $config;
        }
        return $this;
    }

    public function config()
    {
        if (!$this->config)
        {
            $this->config = new \Rapid\Config();
        }
        return $this->config;
    }

    public function clearConfig()
    {
        $this->config = null;
        return $this;
    }
}