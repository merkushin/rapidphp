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
        list($uri, $tail) = $this->getUriAndTail();
        if (!$this->processCustomRoutes())
        {
            $this->processUri($uri);
        }
        $this->processTail($tail);

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

    protected function getUriAndTail()
    {
        $uri = $this->request->uri();

        $parts = explode('?', $uri);
        if (count($parts) == 2)
        {
            list($uri, $tail) = $parts;
        }
        else
        {
            $uri = array_shift($parts);
            $tail = array();
        }

        return array($uri, $tail);
    }

    protected function processUri($uri)
    {
        if (substr($uri, -1) == '/')
        {
            $uri = substr($uri, 0, -1);
        }
        $parts = explode('/', $uri);
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

    protected function processTail($tail)
    {
        if (!$tail)
        {
            return;
        }

        $paramsMaxIndex = count($this->request->params());
        foreach(explode('&', $tail) as $keyValuePair)
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