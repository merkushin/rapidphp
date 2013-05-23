<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

use Rapid\Request\Container;

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

    public function __construct()
    {
        $this->post = new Container($_POST);
        $this->get = new Container($_GET);
        $this->request = new Container($_REQUEST);
        $this->server = new Container($_SERVER);
        $this->cookie = new Container($_COOKIE);
        $this->files = new Container($_FILES);
    }

    public function isPost()
    {
        if (isset($this->server['REQUEST_METHOD'])) {
            return $this->server['REQUEST_METHOD'] == 'POST';
        }

        return false;
    }

    public function isXmlHttpRequest()
    {
        if (isset($this->server['HTTP_X_REQUESTED_WITH']) &&
            $this->server['HTTP_X_REQUESTED_WITH'] == 'XmlHttpRequest'
        ) {
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
        $uri = '';
        if (isset($this->server['REQUEST_URI'])) {
            $uri = $this->server['REQUEST_URI'];
        }

        $uri = ltrim($uri, '/');

        if (!$this->hasQueryPart($uri)) {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    public function path()
    {
        if (!$this->hasQueryPart()) {
            return $this->uri();
        }

        list($path, $query) = $this->pathAndQuery();

        trim($path, '/');

        return $path;
    }

    public function query()
    {
        if (!$this->hasQueryPart()) {
            return '';
        }
        list($path, $query) = $this->pathAndQuery();
        return $query;
    }

    protected function pathAndQuery()
    {
        $parts = explode('?', $this->uri());
        if (count($parts) == 2) {
            list($path, $query) = $parts;
        } else {
            $path = array_shift($parts);
            $query = implode('?', $parts);
        }

        return array($path, $query);
    }

    /**
     * @param string $uri This is optional, but we should use this param inside uri() method,
     * otherwise we will get infinite loop
     *
     * @return bool
     */
    protected function hasQueryPart($uri = null)
    {
        if (is_null($uri)) {
            $uri = $this->uri();
        }

        return strpos($uri, '?') !== false;
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

    public function session()
    {
        return isset($_SESSION)
            ? $_SESSION
            : array();
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

    public function param($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }
}