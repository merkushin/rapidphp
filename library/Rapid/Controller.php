<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Controller
{
    /**
     * @var \Rapid\Dispatcher
     */
    protected $dispatcher;
    /**
     * @var \Rapid\Request
     */
    protected $request;
    /**
     * @var \Rapid\View
     */
    protected $view;

    public function __construct($dispatcher, $request)
    {
        $this->dispatcher = $dispatcher;
        $this->request = $request;
    }

    /**
     * @param View $view
     *
     * @return Controller
     */
    public function setView(\Rapid\View $view)
    {
        $this->view = $view;
        return $this;
    }

    public function view()
    {
        return $this->view;
    }

    public function preDispatch()
    {

    }

    public function process($action)
    {
        $variables = (array)$this->$action();
        if ($this->view)
        {
            $this->view->setVariables($variables);
            return $this->view->render();
        }
    }

    public function postDispatch()
    {

    }

    public function name()
    {
        $fullClassName = $this->fullName();
        $matches = array();
        preg_match('/^(.+)Controller$/', $fullClassName, $matches);
        if (!$matches)
        {
            throw new \Rapid\Controller\Exception('Controller has no name');
        }
        $name = preg_replace('/[A-Z]/', '_$0', $matches[1]);
        $name = strtolower(substr($name, 1));
        return $name;
    }

    public function fullName()
    {
        $parts = explode('\\', get_class($this));
        $fullClassName = $parts[count($parts) - 1];
        return $fullClassName;
    }
}