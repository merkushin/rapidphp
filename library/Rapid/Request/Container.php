<?php
namespace Rapid\Request;

class Container
{
    private $container = array();

    public function __construct($array)
    {
        $this->container = $array;
    }

    public function get($key, $default = null)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }
        return $default;
    }
}