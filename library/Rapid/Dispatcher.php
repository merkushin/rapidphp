<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Dispatcher
{
    protected $application;
    protected $request;

    public function __construct(\Rapid\Application $application, \Rapid\Request $request)
    {
        $this->application = $application;
        $this->request = $request;
    }

    public function dispatch()
    {
        $requestModule = $this->request->module();
        $requestController = $this->request->controller();
        $requestAction = $this->request->action();
        $controllerName = $this->buildControllerName($requestController, $requestModule);

        /**
         * @var \Rapid\Controller $controller
         */
        $controller = new $controllerName($this, $this->request);

        $action = $this->processActionName($requestAction);
        if (!method_exists($controller, $action))
        {
            throw new \Rapid\Dispatcher\Exception('Action not found');
        }

        $controller->preDispatch();
        $controller->$action();
        $controller->postDispatch();
    }

    public function getRequest()
    {
        return $this
            ->request;
    }

    protected function buildControllerName($controller, $module)
    {
        if ($module)
        {
            $this->validateModule($module);
        }

        $controllerName = $this->processControllerName($controller);

        $namespaces = $this->moduleToNamespaces($module);

        return '\\Controllers\\' . $namespaces . $controllerName;
    }

    protected function processControllerName($controller)
    {
        return $this->processUriName($controller) . 'Controller';
    }

    protected function moduleToNamespaces($module)
    {
        if (!$module)
        {
            return '';
        }

        if (substr($module, -1) == '/')
        {
            $module = substr($module, 0, strlen($module) -1);
        }

        return implode(
            '\\',
            explode(
                '/',
                implode('',
                    array_map(
                        'ucfirst',
                        explode('_', $module)
                    )
                )
            )
        ) . '\\';
    }

    protected function processActionName($action)
    {
        return $this->processUriName($action) . 'Action';
    }

    protected function processUriName($name)
    {
        $name = implode('',
            array_map(
                'ucfirst',
                preg_split('/[_-]+/', $name)
            )
        );
        return $name;
    }

    protected function validateModule($module)
    {
        if ($this->application->hasModule($module))
        {
            return true;
        }

        throw new \Rapid\Dispatcher\Exception("Module not found");
    }
}