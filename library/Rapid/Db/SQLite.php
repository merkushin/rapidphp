<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Db;

class SQLite extends \Rapid\Db
{
    protected function dsn()
    {
        $dsn = sprintf('%s:', $this->options['driver']);

        if (empty($this->options['dbname']))
        {
            throw new \Rapid\Db\Exception('Database name not set');
        }

        $dsn .= $this->options['dbname'];

        return $dsn;
    }
}