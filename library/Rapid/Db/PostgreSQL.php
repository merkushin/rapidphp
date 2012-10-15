<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Db;

class PostgreSQL extends \Rapid\Db
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

        if (!empty($this->options['dbname']))
        {
            $dsn .= sprintf(';dbname=%s', $this->options['dbname']);
        }

        if (!empty($this->options['user']))
        {
            $dsn .= sprintf(';user=%s', $this->options['user']);
        }

        if (!empty($this->options['password']))
        {
            $dsn .= sprintf(';password=%s', $this->options['password']);
        }

        return $dsn;
    }
}