<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Router;

class Route implements \Rapid\Router\RouteInterface
{
    protected $rule;

    protected $params;
    protected $values;

    protected $defaultModule;
    protected $defaultController;
    protected $defaultAction;

    public function __construct($rule, $controller = 'index', $action = 'index', $module = '')
    {
        $this->rule = $rule;
        if (preg_match('/:\w+/', $rule))
        {
            $this->rule = $this->extractRulePlaceholders($rule);
        }

        if (!is_null($module))
        {
            $this->defaultModule = $module;
        }

        if ($controller)
        {
            $this->defaultController = $controller;
        }

        if ($action)
        {
            $this->defaultAction = $action;
        }
    }

    /**
     * @param \Rapid\Request $request
     *
     * @return bool
     */
    public function pass(\Rapid\Request $request)
    {
        $rule = $this->prepareRuleForRegExp();
        if (!preg_match($rule, $request->uri(), $matches))
        {
            return false;
        }

        $this->fillInValues($matches);
        $this->setRequestValues($request);
        return true;
    }

    protected  function extractRulePlaceholders($rule)
    {
        preg_match_all('/:(\w+)/', $rule, $matches);

        $placeholderCount = count($matches[0]);
        for ($i = 0; $i < $placeholderCount; $i++)
        {
            $placeholder = $matches[0][$i];
            $paramName = $matches[1][$i];

            $rule = str_replace($placeholder, '(\w+)', $rule);
            $this->params[$i + 1] = $paramName;
        }

        return $rule;
    }

    protected  function prepareRuleForRegExp()
    {
        return sprintf('#%s#i', $this->rule);
    }

    protected function fillInValues($matches)
    {
        if (!$this->params)
        {
            return;
        }

        foreach ($matches as $key => $value)
        {
            if (!$key)
            {
                continue;
            }

            $this->values[$key] = $value;
        }
    }

    protected function setRequestValues(\Rapid\Request $request)
    {
        $request->setModule($this->moduleName())
            ->setController($this->controllerName())
            ->setAction($this->actionName());

        foreach ($this->additionalParams() as $key => $value)
        {
            $request->setParam($key, $value);
        }
    }

    protected function moduleName()
    {
        $module = $this->defaultModule;
        if ($key = array_search('module', $this->params))
        {
            $module = !empty($this->values[$key])
                ? $this->values[$key] . '/'
                : $module;
        }
        return $module;
    }

    protected function controllerName()
    {
        $controller = $this->defaultController;
        if ($key = array_search('controller', $this->params))
        {
            $controller = !empty($this->values[$key])
                ? $this->values[$key]
                : $controller;
        }
        return $controller;
    }

    protected function actionName()
    {
        $action = $this->defaultAction;
        if ($key = array_search('action', $this->params))
        {
            $action = !empty($this->values[$key])
                ? $this->values[$key]
                : $action;
        }
        return $action;
    }

    protected function additionalParams()
    {
        $ret = array();
        foreach ($this->params as $key => $name)
        {
            if (in_array($name, array('module', 'controller', 'action')))
            {
                continue;
            }

            if (!isset($this->values[$key]))
            {
                continue;
            }

            $ret[$name] = $this->values[$key];
        }
        return $ret;
    }
}