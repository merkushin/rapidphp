<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Form;

abstract class Element
{
    protected $label;
    protected $value;
    protected $attributes = array();
    protected $errors = array();
    protected $validators = array();

    /**
     * @return string
     */
    abstract public function render();

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function value()
    {
        return $this->value;
    }

    public function setAttribute($name, $value)
    {
        if ($name == 'value') {
            $this->setValue($value);
        } else {
            $this->attributes[$name] = $value;
        }
        return $this;
    }

    protected function attributes()
    {
        $pairs = array();
        foreach ($this->attributes as $name => $value) {
            $value = strtr($value, '"', '\"');
            $pairs[] = sprintf('%s="%s"', $name, $value);
        }
        return implode(' ', $pairs);
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function label()
    {
        if (!$this->label) {
            return '';
        }

        return sprintf(
            '<label%s>%s</label>',
            $this->labelFor(),
            $this->label
        );
    }

    protected function labelFor()
    {
        return $this->id()
            ? sprintf(' for="%s"', $this->id())
            : '';
    }

    protected function id()
    {
        return isset($this->attributes['id'])
            ? $this->attributes['id']
            : '';
    }

    public function setErrors(array $messages)
    {
        $this->errors = $messages;
        return $this;
    }

    public function addError($message)
    {
        $this->errors[] = $message;
        return $this;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function setValidators(array $validators)
    {
        $this->validators = $validators;
        return $this;
    }

    public function addValidator(\Rapid\Form\Validator $validator)
    {
        $this->validators[] = $validator;
        return $this;
    }

    public function validators()
    {
        return $this->validators;
    }
}