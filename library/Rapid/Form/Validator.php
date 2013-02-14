<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Form;

abstract class Validator
{
    /**
     * @var \Rapid\Model
     */
    protected $model;

    protected $elementName;

    public function setModel(\Rapid\Model $model)
    {
        $this->model = $model;
    }

    public function setElementName($name)
    {
        $this->elementName = $name;
    }

    protected function getValue(array $data)
    {
        $value = null;
        if ($this->model) {
            return $this->getValueByModelAndName($data);
        }

        return $this->getValueByName($data);
    }

    protected function getValueByModelAndName(array $data)
    {
        if (!isset($data[$this->model->name()][$this->elementName])) {
            throw new \Rapid\Form\Validator\Exception\DoesntExist();
        }
        return $data[$this->model->name()][$this->elementName];
    }

    protected function getValueByName(array $data)
    {
        if (!isset($data[$this->elementName])) {
            throw new \Rapid\Form\Validator\Exception\DoesntExist();
        }
        return $data[$this->elementName];
    }

    abstract public function isValid(array $data);

    abstract public function getError();
}