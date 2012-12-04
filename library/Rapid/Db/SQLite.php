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

        if (empty($this->options['dbname'])) {
            throw new \Rapid\Db\Exception('Database name not set');
        }

        $dsn .= $this->options['dbname'];

        return $dsn;
    }

    /**
     * @param string $tablename
     * @param array $params
     *
     * @return int|void
     */
    public function insert($tablename, array $params)
    {
        $query = 'INSERT INTO %s(%s) VALUES(%s)';
        $fields = array();
        $placeholders = array();
        foreach ($params as $field => $value) {
            $fields[] = $field;
            $placeholders[] = sprintf(':%s', $field);
        }
        $query = sprintf($query, $tablename, implode(', ', $fields), implode(', ', $placeholders));
        $this->executePreparedStatement($query, $params);
        return $this->driver->lastInsertId();
    }

    /**
     * @param string $tablename
     * @param array $params
     * @param array $where
     *
     * @return void
     */
    public function update($tablename, array $params, $where = array())
    {
        $query = 'UPDATE %s SET %s';
        $set = array();
        foreach ($params as $field => $value) {
            $set[] = sprintf('%s=:%s', $field, $field);
        }
        $query = sprintf($query, $tablename, implode(', ', $set));

        if (count($where)) {
            list($whereClause, $whereParams) = $this->prepareWhere($where);
            $query .= $whereClause;
            $params = array_merge($params, $whereParams);
        }

        $this->executePreparedStatement($query, $params);
    }

    /**
     * @param string $tablename
     * @param array $where
     *
     * @return void
     */
    public function delete($tablename, $where = array())
    {
        $query = sprintf('DELETE FROM %s', $tablename);
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