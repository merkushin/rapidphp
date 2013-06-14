<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Db;

/**
 * Class PostgreSQL
 *
 * @package Rapid\Db
 *
 * Important: I'm not PostgreSQL developer, so this class is just example
 */
class PostgreSQL extends \Rapid\Db
{
    protected function dsn()
    {
        $dsn = 'mysql:';
        if (!empty($this->options['host'])) {
            $dsn .= sprintf('host=%s', $this->options['host']);
            if (!empty($this->options['port'])) {
                $dsn .= sprintf(';port=%d', $this->options['port']);
            }
        }

        if (!empty($this->options['dbname'])) {
            $dsn .= sprintf(';dbname=%s', $this->options['dbname']);
        }

        if (!empty($this->options['user'])) {
            $dsn .= sprintf(';user=%s', $this->options['user']);
        }

        if (!empty($this->options['password'])) {
            $dsn .= sprintf(';password=%s', $this->options['password']);
        }

        return $dsn;
    }

    /**
     * @param string $tableName
     * @param array $params
     *
     * @return int|void
     */
    public function insert($tableName, array $params)
    {
        $query = 'INSERT INTO %s(%s) VALUES(%s)';
        $fields = array();
        $placeholders = array();
        foreach ($params as $field => $value) {
            $fields[] = $field;
            $placeholders[] = sprintf(':%s', $field);
        }
        $query = sprintf($query, $tableName, implode(', ', $fields), implode(', ', $placeholders));
        $this->executePreparedStatement($query, $params);
        return $this->driver->lastInsertId();
    }

    /**
     * @param string $tableName
     * @param array $params
     * @param array $where
     *
     * @return void
     */
    public function update($tableName, array $params, $where = array())
    {
        $query = 'UPDATE %s SET %s';
        $set = array();
        foreach ($params as $field => $value) {
            $set[] = sprintf('%s=:%s', $field, $field);
        }
        $query = sprintf($query, $tableName, implode(', ', $set));

        if (count($where)) {
            list($whereClause, $whereParams) = $this->prepareWhere($where);
            $query .= $whereClause;
            $params = array_merge($params, $whereParams);
        }

        $this->executePreparedStatement($query, $params);
    }

    /**
     * @param string $tableName
     * @param array $where
     *
     * @return void
     */
    public function delete($tableName, $where = array())
    {
        $query = sprintf('DELETE FROM %s', $tableName);
        $params = array();
        if (count($where)) {
            list($whereClause, $whereParams) = $this->prepareWhere($where);
            $query .= $whereClause;
            $params = array_merge($params, $whereParams);
        }
        $this->executePreparedStatement($query, $params);
    }

    /**
     * @param array $where
     *
     * @return array
     */
    public function prepareWhere(array $where)
    {
        $clause = ' WHERE ';
        $params = array();

        $clauseStatements = array();
        foreach ($where as $field => $value) {
            if (is_numeric($field)) {
                $clauseStatements[] = $value;
            } else {
                /**
                 * @todo build query with IN () if $value is array
                 */
                $clauseStatements[] = sprintf('%s=:%s', $field, $field);
                $params[$field] = $value;

            }
        }
        $clause .= implode(' AND ', $clauseStatements);

        return array(
            $clause, $params
        );
    }
}