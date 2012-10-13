<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Controller
{
    protected $dispatcher;
    protected $request;

    public function __construct($dispatcher, $request)
    {
        $this->dispatcher = $dispatcher;
        $this->request = $request;
    }

    public function preDispatch()
    {

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