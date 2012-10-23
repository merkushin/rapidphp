<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Request
{
    protected $module;
    protected $controller;
    protected $action;
    protected $params = array();
    protected $post;
    protected $get;
    protected $request;
    protected $server;
    protected $cookie;
    protected $files;
    protected $session;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->request = $_REQUEST;
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->session = isset($_SESSION) ? $_SESSION : array();
    }

    public function isPost()
    {
        if (isset($this->server['REQUEST_METHOD']))
        {
            return $this->server['REQUEST_METHOD'] == 'POST';
        }

        return false;
    }

    public function isXmlHttpRequest()
    {
        if (isset($this->server['HTTP_X_REQUESTED_WITH']) &&
            $this->server['HTTP_X_REQUESTED_WITH'] == 'XmlHttpRequest')
        {
            return true;
        }

        return false;
    }

    /**
     * Returns request uri without first forward slash
     *
     * @return string
     */
    public function uri()
    {
        if (isset($this->server['REQUEST_URI']))
        {
            $uri = $this->server['REQUEST_URI'];
        }

        if (substr($uri, 0, 1) == '/')
        {
            $uri = substr($uri, 1);
        }

        if (substr($uri, -1) == '/')
        {
            $uri = substr($uri, 0, -1);
        }

        return $uri;
    }

    public function get()
    {
        return $this->get;
    }

    public function post()
    {
        return $this->post;
    }

    public function request()
    {
        return $this->request;
    }

    public function server()
    {
        return $this->server;
    }

    public function cookie()
    {
        return $this->cookie;
    }

    public function files()
    {
        return $this->files;
    }

    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function module()
    {
        return $this->module;
    }

    public function controller()
    {
        return $this->controller;
    }

    public function action()
    {
        return $this->action;
    }

    public function params()
    {
        return $this->params;
    }

    public function param($name, $default=null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }
}