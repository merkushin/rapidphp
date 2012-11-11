<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Model
{
    /**
     * @var array
     */
    protected $_params      = array();

    /**
     * @var array
     */
    protected $_modified    = array();

    /**
     * @return null|int
     */
    public function id()
    {
        return isset($this->_params['id']) ? $this->_params['id'] : null;
    }

    /**
     * @param int $value
     *
     * @return \Rapid\Model
     */
    public function setId($value)
    {
        $this->_params['id'] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param bool $init Mark field as modified if false
     * @return \Rapid\Model
     */
    public function setProperty($name, $value, $init = false)
    {
        if (!$init && (!isset($this->_params[ $name ]) || $this->_params[ $name ] != $value))
        {
            $this->_modified[] = $name;
        }

        $this->_params[ $name ] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setProperty($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function property($name)
    {
        $val = isset($this->_params[ $name ]) ?
            $this->_params[ $name ] : null;
        return $val;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->property($name);
    }

    /**
     * @return array
     */
    public function properties()
    {
        return $this->_params;
    }

    /**
     * @return array
     */
    public function modifiedProperties()
    {
        $return = array();

        foreach ($this->_modified as $name)
        {
            $return[ $name ] = $this->property( $name );
        }

        return $return;
    }

    /**
     * @param array $data
     * @param bool $init Mark fields as modified if false
     * @return \Rapid\Model
     */
    public function setProperties(array $data, $init = false)
    {
        foreach ($data as $name => $value)
        {
            $this->setProperty($name, $value, $init);
        }

        return $this;
    }

    public function name()
    {
        $name = preg_replace('/[A-Z]/', '_$0', $this->fullName());
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