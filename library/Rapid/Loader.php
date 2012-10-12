<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Loader
{
    /**
     * @var \Rapid\Application
     */
    protected static $application;

    public static function setAutoLoaders()
    {
        spl_autoload_register(array('\Rapid\Loader', 'basicLoader'));
    }

    public static function setApplication(\Rapid\Application $application)
    {
        self::$application = $application;
    }

    public static function basicLoader($class)
    {
        if (self::$application && preg_match('/(\w+)Model$/', $class))
        {
            self::loadModel($class);
        }
        elseif (self::$application && preg_match('/(\w+)Controller$/', $class))
        {
            self::loadController($class);
        }
        else
        {
            self::loadLibrary($class);
        }
    }

    public static function loadModel($class)
    {

    }

    public static function loadController($class)
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $class);
        $class = array_pop($pathParts);

        $classNameParts = explode('\\', $class);
        $className = array_pop($classNameParts);

        $path = implode(DIRECTORY_SEPARATOR, array_map('strtolower', $classNameParts));
        $path .= DIRECTORY_SEPARATOR. $className . '.php';


        if (file_exists(self::$application->path() . $path))
        {
            require_once self::$application->path() . $path;
            return;
        }


        throw new Loader\Exception('Controller not found');
    }

    public static function loadLibrary($class)
    {
        $filename = implode(DIRECTORY_SEPARATOR, explode('\\', $class)) . '.php';
        require_once $filename;
    }
}