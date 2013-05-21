<?php
namespace Rapid\Request;

class Container implements \ArrayAccess
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

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }


    public function offsetGet($offset)
    {
        return isset($this->container[$offset])
            ? $this->container[$offset]
            : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}