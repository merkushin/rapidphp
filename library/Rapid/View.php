<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class View
{
    /**
     * @var string Path to view file
     */
    protected $file;

    /**
     * @var array View variables
     */
    protected $variables = array();

    /**
     * Set view file
     *
     * @param $path
     *
     * @return View
     */
    public function setFile($path)
    {
        $this->file = $path;
        return $this;
    }

    /**
     * Return view file path
     *
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    public function setVariables(array $vars)
    {
        foreach ($vars as $name => $value)
        {
            $this->setVariable($name, $value);
        }
        return $this;
    }

    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }

    public function variables()
    {
        return $this->variables;
    }

    public function variable($name)
    {
        return isset($this->variables[$name])
            ? $this->variables[$name]
            : null;
    }

    public function __get($name)
    {
        return $this->variable($name);
    }

    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }

    public function render()
    {
        ob_start();
        if (!file_exists($this->file))
        {
            throw new \Rapid\View\Exception("View file not found");
        }
        extract($this->variables);
        include $this->file;
        return ob_get_clean();
    }

    public function display()
    {
        echo $this->render();
    }
}