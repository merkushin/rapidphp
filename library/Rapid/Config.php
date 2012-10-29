<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;


/**
 * Read-only config
 */
class Config
{
    protected $options = array();

    public function __construct($options = array())
    {
        if (is_string($options))
        {
            $options = $this->loadOptionsFromFile($options);
        }
        elseif ($options instanceof \Rapid\Config)
        {
            $options = $options->options();
        }
        elseif (!is_array($options))
        {
            throw new \Rapid\Config\Exception('Unknown type of options data');
        }

        $this->options = $options;
    }

    public function options()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function merge($options)
    {
        if (!($options instanceof \Rapid\Config))
        {
            $options = new \Rapid\Config($options);
        }

        $this->options = array_merge($this->options, $options->options());
    }

    public function get($key)
    {
        if (!isset($this->options[$key]))
        {
            return null;
        }

        if (!is_array($this->options[$key]))
        {
            return $this->options[$key];
        }

        return new Config($this->options[$key]);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    protected function loadOptionsFromFile($filename)
    {
        if (preg_match('/\.php$/i', $filename))
        {
            $ret = $this->loadPhpArray($filename);
        }
        elseif (preg_match('/\.ini/i', $filename))
        {
            $ret = $this->loadIniFile($filename);
        }
        else
        {
            throw new \Rapid\Config\Exception('Unknown config file type');
        }

        if (!is_array($ret))
        {
            throw new Exception('Config file contains incorrect data');
        }

        return $ret;
    }

    protected function loadPhpArray($filename)
    {
        return include $filename;
    }

    protected function loadIniFile($filename)
    {
        return parse_ini_file($filename, true);
    }
}