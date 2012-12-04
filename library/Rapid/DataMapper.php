<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class DataMapper
{
    protected $modelClass;
    protected $tablename;
    protected $db;
    protected $connectionName = 'default';

    public function tablename()
    {
        return $this->tablename;
    }

    public function modelClass()
    {
        return $this->modelClass;
    }

    /**
     * @return \Rapid\Db
     */
    protected function db()
    {
        if (!$this->db) {
            $connectionName = $this->connectionName ? $this->connectionName : 'default';
            $this->db = \Rapid\Db::get($connectionName);
        }

        return $this->db;
    }

    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
        return $this;
    }

    public function setDb(\Rapid\Db $db)
    {
        $this->db = $db;
        return $this;
    }

    public function setConnectionName($name)
    {
        $this->connectionName = $name;
        return $this;
    }

    public function save(\Rapid\Model $model)
    {
        if (null === ($id = $model->id())) {
            $data = $model->properties();
            unset($data['id']);
            $id = $this->db()->insert($this->tablename, $data);
            $model->setId($id);
        } else {
            $data = $model->modifiedProperties();
            if (count($data)) {
                $this->db()->update($this->tablename, $data, array('id' => $id));
            }
        }
    }

    public function delete(\Rapid\Model $model)
    {
        if (!$id = $model->id()) {
            return false;
        }

        return $this->db()->delete($this->tablename, array('id' => $id));
    }

    public function find($params = null, $create = false)
    {
        if (!$params) {
            return $this->fetchAll();
        } elseif (is_numeric($params) || is_null($params)) {
            return $this->findById($params, $create);
        }

        $params = (array)$params;
        return $this->fetchAll($params);
    }

    public function findById($id, $create = false)
    {
        $select = sprintf('SELECT * FROM %s WHERE id=:id', $this->tablename);
        $row = $this->db()->fetchRow($select, array('id' => $id));

        /**
         * @var \Rapid\Model $model
         */
        $model = new $this->modelClass;

        if (!$row) {
            return $create ? $model : null;
        }

        $model->setProperties($row, true);

        return $model;
    }

    public function fetchAll($where = array())
    {
        $select = sprintf('SELECT * FROM %s', $this->tablename);
        $params = array();
        if ($where) {
            list($whereStatement, $whereParams) = $this->db()->prepareWhere($where);
            $select .= $whereStatement;
            $params = $whereParams;
        }

        $rows = $this->db()->fetchAll($select, $params);

        $ret = array();
        foreach ($rows as $row) {
            $m = new $this->modelClass;
            /**
             * @var \Rapid\Model $m
             */
            $m->setProperties($row, true);
            $ret[] = $m;
        }

        return $ret;
    }
}