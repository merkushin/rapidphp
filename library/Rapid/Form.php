<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid;

class Form
{
    /**
     * @var \Rapid\Model
     */
    protected $model;

    /**
     * @var array of \Rapid\Form\Element
     */
    protected $elements = array();

    /**
     * @var array of \Rapid\Form\Validator
     */
    protected $validators = array();

    protected $errors =  array();

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * Add validator for element
     *
     * @param string $name Element name
     * @param Form\Validator $validator
     *
     * @return Form
     */
    public function addValidator($name, \Rapid\Form\Validator $validator)
    {
        $validator->setModel($this->model);
        $validator->setElementName($name);
        $this->validators[$name] = $validator;
        return $this;
    }

    /**
     * Check if data is valid
     *
     * @param array $data
     *
     * @return bool
     */
    public function isValid(array $data)
    {
        $ret = true;
        /**
         * @var \Rapid\Form\Validator $validator
         */
        foreach ($this->validators as $name => $validator) {
            if (!$validator->isValid($data)) {
                $this->errors[$name] = $validator->getError();
                $ret = false;
            }
        }
        return $ret;
    }

    /**
     * Add element to the form
     *
     * @param string $name
     * @param \Rapid\Form\Element $element
     *
     * @return Form
     */
    public function addElement($name, \Rapid\Form\Element $element)
    {
        $element->setAttribute('name', $this->elementName($name));
        $element->setAttribute('id', $this->elementId($name));
        if ($this->model && $this->model->property($name)) {
            $element->setValue($this->model->property($name));
        }
        $this->elements[$name] = $element;
        return $this;
    }

    /**
     * Build name for element
     *
     * @param string $name
     *
     * @return string
     */
    protected function elementName($name)
    {
        return $this->model
            ? sprintf('%s[%s]', $this->model->name(), $name)
            : $name;
    }

    /**
     * Build id for element
     *
     * @param string $name
     *
     * @return string
     */
    protected function elementId($name)
    {
        return sprintf('%s_id', $name);
    }

    /**
     * @param string $name
     *
     * @return \Rapid\Form\Element
     */
    public function element($name)
    {
        return isset($this->elements[$name])
            ? $this->elements[$name]
            : null;
    }

    /**
     * @param array $values
     *
     * @return Form
     */
    public function populate($values)
    {
        foreach ($values as $name => $value) {
            if ($element = $this->element($name)) {
                $element->setValue($value);
            }
        }
        return $this;
    }

    /**
     * Render elements
     *
     * @param string $type
     *
     * @return string
     */
    public function render($type = 'li')
    {
        switch ($type) {
            case 'p':
                $html = $this->asP();
                break;
            case 'tr':
                $html = $this->asTr();
                break;
            default:
                $html = $this->asLi();
        }
        return $html;
    }

    /**
     * Render elements as paragraphs
     *
     * @return string
     */
    public function asP()
    {
        $html = '';
        /**
         * @var \Rapid\Form\Element $element
         */
        foreach ($this->elements as $element) {
            $html .= sprintf('<p>%s %s</p>' . PHP_EOL, $element->label(), $element->render());
        }
        return $html;
    }

    /**
     * Render elements as list items
     *
     * @return string
     */
    public function asLi()
    {
        $html = '';
        /**
         * @var \Rapid\Form\Element $element
         */
        foreach ($this->elements as $element) {
            $html .= sprintf('<li>%s %s</li>' . PHP_EOL, $element->label(), $element->render());
        }
        return $html;
    }

    /**
     * Render elements as table rows
     *
     * @return string
     */
    public function asTr()
    {
        $html = '';
        /**
         * @var \Rapid\Form\Element $element
         */
        foreach ($this->elements as $element) {
            $html .= sprintf('<tr><td>%s</td><td>%s</td></tr>' . PHP_EOL, $element->label(), $element->render());
        }
        return $html;
    }

    public function addError($error)
    {
        $this->errors[] = $error;
        return $this;
    }

    public function addErrors(array $errors)
    {
        $this->errors = array_merge(
            $this->errors,
            $errors
        );
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function clearErrors()
    {
        $this->errors = array();
        return $this;
    }
}