<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Session
{
    static public function init($sessionName = null)
    {
        self::name($sessionName);
        self::start();
        self::regenerateId();
    }

    /**
     * @param string $name
     */
    static public function name($name = null)
    {
        session_name($name);
    }

    static public function start()
    {
        session_start();
    }

    static public function regenerateId()
    {
        session_regenerate_id();
    }

    /**
     * @return string
     */
    static public function sessionId()
    {
        return session_id();
    }
}