<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Application;

class Bootstrap
{
    /**
     * @var \Rapid\Application
     */
    protected $application;

    protected $methods;

    protected $results;

    public function __construct(\Rapid\Application $application)
    {
        $this->application = $application;
        $this->results = array();
    }

    protected function bootstrapMethods()
    {
        $results = array();
        foreach ($this->methods() as $key => $method) {
            $results[$key] = $this->$method();
        }
        $this->results = $results;
    }

    protected function methods()
    {
        if ($this->methods) {
            return $this->methods;
        }

        $this->methods = array();

        $methodNames = get_class_methods($this);
        foreach ($methodNames as $method) {
            if (strlen($method) > 4 && substr($method, 0, 4) == 'init') {
                $key = strtolower(substr($method, 4));
                $this->methods[$key] = $method;
            }
        }

        return $this->methods;
    }

    public function bootstrap()
    {
        $this->bootstrapMethods();
        return $this;
    }

    public function results()
    {
        return $this->results;
    }
}