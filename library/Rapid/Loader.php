<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

require_once 'Rapid/Loader/Exception.php';

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
        if (self::$application && substr($class, 0, 7) == 'Models\\')
        {
            self::loadApplicationClass($class);
        }
        elseif (self::$application && substr($class, 0, 12) == 'Controllers\\')
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
        if (!self::loadApplicationClass($class))
        {
            throw new Loader\Exception('Model not found');
        }
    }

    public static function loadController($class)
    {
        if (!self::loadApplicationClass($class))
        {
            throw new Loader\Exception('Controller not found');
        }
    }

    public static function loadApplicationClass($class)
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $class);
        $class = array_pop($pathParts);

        $classNameParts = explode('\\', $class);
        $className = array_pop($classNameParts);

        $path = implode(DIRECTORY_SEPARATOR, array_map('strtolower', $classNameParts));
        $path .= DIRECTORY_SEPARATOR. $className . '.php';


        if (file_exists(self::$application->path() . $path))
        {
            include_once self::$application->path() . $path;
            return true;
        }

        return false;
    }

    public static function loadLibrary($class)
    {
        $filename = implode(DIRECTORY_SEPARATOR, explode('\\', $class)) . '.php';
        $includePaths = explode(PATH_SEPARATOR, get_include_path());

        foreach ($includePaths as $path)
        {
            $include = $path . DIRECTORY_SEPARATOR . $filename;
            if (!file_exists($include))
            {
                continue;
            }

            include_once $include;
            return;
        }

        throw new \Rapid\Loader\Exception('Library class not found: ' . $filename);
    }
}