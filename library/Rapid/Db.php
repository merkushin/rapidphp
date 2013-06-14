<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

abstract class Db
{
    protected $options;

    protected $driver;

    private static $instances;

    /**
     * @param string|array $options
     * @param string $instanceName
     *
     * @return \Rapid\Db
     */
    public static function factory($options = array(), $instanceName = '')
    {
        if (is_string($options) && strlen($options)) {
            $options = self::parseString($options);
        }

        $instanceName = $instanceName ? $instanceName : 'default';

        $instance = null;
        switch ($options['driver']) {
            case 'mysql' :
                $instance = new \Rapid\Db\MySQL($options);
                break;
            case 'pgsql' :
                $instance = new \Rapid\Db\PostgreSQL($options);
                break;
            case 'sqlite' :
                $instance = new \Rapid\Db\SQLite($options);
                break;
        }

        self::$instances[$instanceName] = $instance;

        return $instance;
    }

    /**
     * @param string $instanceName
     *
     * @return \Rapid\Db
     */
    public static function get($instanceName = '')
    {
        $instanceName = $instanceName ? $instanceName : 'default';
        return isset(self::$instances[$instanceName])
            ? self::$instances[$instanceName]
            : null;
    }

    public function __construct($options)
    {
        if (!count($options)) {
            throw new \Rapid\Db\Exception('No options given');
        }

        $this->setOptions($options);
        $dsn = $this->dsn();
        $this->driver = new \PDO($dsn, $options['user'], $options['password']);
    }

    protected static function parseString($options)
    {
        if (strpos($options, 'sqlite') !== false) {
            return self::parseSQLiteString($options);
        }

        list($driver, $config) = explode(':', $options);

        $ret = array();
        $ret['driver'] = $driver;
        $configStrings = explode(';', $config);
        foreach ($configStrings as $keyValueString) {
            list($optionName, $optionValue) = explode('=', $keyValueString);
            $ret[$optionName] = $optionValue;
        }
        return $ret;
    }

    protected static function parseSQLiteString($string)
    {
        $ret = array();

        $colonPosition = strpos($string, ':');
        $ret['driver'] = substr($string, 0, $colonPosition);
        $ret['dbname'] = substr($string, $colonPosition + 1);

        return $ret;
    }

    public function setOptions($options)
    {
        $defaults = array(
            'host' => 'localhost',
            'port' => null,
            'dbname' => '',
            'user' => '',
            'password' => '',
        );
        $options = array_merge($defaults, $options);
        $this->options = $options;
    }

    public function options()
    {
        return $this->options;
    }

    /**
     * Return DSN for given database driver
     *
     * @return string
     */
    abstract protected function dsn();

    /**
     * Run query and return number of affected rows
     *
     * @param $query
     * @param array $params
     *
     * @return int|mixed
     */
    public function query($query, $params = array())
    {
        if (count($params)) {
            $stmt = $this->executePreparedStatement($query, $params);
            return $stmt->rowCount();
        }

        return $this->driver->exec($query);
    }

    /**
     * Executes query and returns associated array with all result rows
     *
     * @param $query
     * @param array $params
     *
     * @return mixed
     */
    public function fetchAll($query, $params = array())
    {
        $stmt = $this->executePreparedStatement($query, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Executes query and returns associated array with result row
     *
     * @param $query
     * @param array $params
     *
     * @return mixed
     */
    public function fetchRow($query, $params = array())
    {
        $stmt = $this->executePreparedStatement($query, $params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Executes query and returns array with single column
     *
     * @param $query
     * @param array $params
     * @param int $column
     *
     * @return mixed
     */
    public function fetchColumn($query, $params = array(), $column = 0)
    {
        $stmt = $this->executePreparedStatement($query, $params);
        $columnCount = $stmt->columnCount();

        if (!$columnCount) {
            return null;
        }

        $column = $column >= 0 && $column <= $columnCount - 1 ? (int)$column : 0;
        $ret = array();
        while (($value = $stmt->fetchColumn($column)) !== false) {
            $ret[] = $value;
        }
        return $ret;
    }

    /**
     * Executes query and returns value
     *
     * @param $query
     * @param array $params
     *
     * @return mixed
     */
    public function fetchOne($query, $params = array())
    {
        $stmt = $this->executePreparedStatement($query, $params);
        $value = $stmt->fetchColumn();
        return $value;
    }

    /**
     * @param string $query
     * @param array $params
     *
     * @return \PDOStatement
     * @throws Db\Exception
     */
    protected function executePreparedStatement($query, $params)
    {
        $stmt = $this->driver->prepare($query);
        if (!$stmt->execute($params)) {
            throw new \Rapid\Db\Exception(
                'Error execution query: ' . implode(PHP_EOL, $stmt->errorInfo())
            );
        }
        return $stmt;
    }

    /**
     * @param $tableName
     * @param array $params
     *
     * @return mixed
     */
    abstract public function insert($tableName, array $params);

    /**
     * @param string $tableName
     * @param array $params
     * @param array $where
     *
     * @return mixed
     */
    abstract public function update($tableName, array $params, $where = array());

    /**
     * @param string $tableName
     * @param array $where
     *
     * @return mixed
     */
    abstract public function delete($tableName, $where = array());

    /**
     * @param array $where
     *
     * @return array
     */
    abstract public function prepareWhere(array $where);
}