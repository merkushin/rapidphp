<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Db;

class MySQL extends \Rapid\Db
{
    protected function dsn()
    {
        $dsn = 'mysql:';
        if (!empty($this->options['host']))
        {
            $dsn .= sprintf('host=%s', $this->options['host']);
            if (!empty($this->options['port']))
            {
                $dsn .= sprintf(';port=%d', $this->options['port']);
            }
        }
        elseif (!empty($this->options['unix_socket']))
        {
            $dsn .= sprintf('unix_socket=%s', $this->options['unix_socket']);
        }

        if (!empty($this->options['dbname']))
        {
            $dsn .= sprintf(';dbname=%s', $this->options['dbname']);
        }

        if (!empty($this->options['charset']))
        {
            $dsn .= sprintf(';charset=%s', $this->options['charset']);
        }

        return $dsn;
    }
}