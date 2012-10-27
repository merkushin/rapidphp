<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Router
{
    /**
     * @var string
     */
    protected $defaultController = 'index';

    /**
     * @var string
     */
    protected $defaultAction = 'index';

    /**
     * @var array
     */
    protected $customRoutes = array();

    /**
     * @var \Rapid\Request
     */
    protected $request;

    /**
     * @var \Rapid\Application
     */
    protected $application;

    public function __construct(\Rapid\Application $application, \Rapid\Request $request)
    {
        $this->application = $application;
        $this->request = $request;
    }

    public function setCustomRoutes(array $routes)
    {
        $this->customRoutes = $routes;
    }

    /**
     * @return \Rapid\Request
     */
    public function route()
    {
        if (!$this->processCustomRoutes())
        {
            $this->processPath();
        }
        $this->processQuery();

        return $this->request;
    }

    protected function processCustomRoutes()
    {
        /**
         * @var \Rapid\Router\RouteInterface $route
         */
        foreach ($this->customRoutes as $route)
        {
            if ($route->pass($this->request))
            {
                return true;
            }
        }

        return false;
    }

    protected function processPath()
    {
        $path = $this->request->path();
        $parts = explode('/', $path);
        $partsCount = count($parts);
        $partsMaxIndex = $partsCount - 1;

        $module = '';
        $controller = '';
        $action = '';

        $paramsKey = '';

        foreach ($parts as $key => $part)
        {
            if (!$controller && $this->application->hasModule($module . $part . '/'))
            {
                $module .= $part . '/';
                continue;
            }

            if (!$controller)
            {
                $controller = $part;
                continue;
            }

            if (!$action)
            {
                $action = $part;
                continue;
            }

            if ($partsMaxIndex > $key && !$paramsKey)
            {
                $paramsKey = $part;
                continue;
            }

            if ($paramsKey)
            {
                $this->request->setParam($paramsKey, $part);
                $paramsKey = '';
            }
            else
            {
                $this->request->setParam($part, 1);
            }
        }

        $this->request
            ->setModule($module)
            ->setController($controller ? $controller : $this->defaultController)
            ->setAction($action ? $action : $this->defaultAction);
    }

    protected function processQuery()
    {
        if (!$this->request->query())
        {
            return;
        }

        $paramsMaxIndex = count($this->request->params());
        foreach(explode('&', $this->request->query()) as $keyValuePair)
        {
            if (strpos($keyValuePair, '=') !== false)
            {
                list($key, $value) = explode('=', $keyValuePair);
            }
            else
            {
                $key = $paramsMaxIndex++;
                $value = $keyValuePair;
            }
            $this->request->setParam($key, $value);
        }
    }
}