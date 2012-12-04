<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Form;

class Select extends ElementInterface
{
    protected $options = array();

    public function __construct($options, $label = '')
    {
        $this->options = $options;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function render()
    {
        return sprintf(
            '<select %s>%s</select>',
            $this->attributes(),
            $this->optionsHtml()
        );
    }

    protected function optionsHtml()
    {
        $html = array();
        foreach ($this->options as $value => $title) {
            $html[] = sprintf(
                '<option value="%s" %s>%s</option>',
                htmlspecialchars($value, ENT_QUOTES),
                $value == $this->value() ? 'selected="selected"' : '',
                $title
            );
        }
        return implode('', $html);
    }
}