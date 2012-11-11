<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Form;

class Input extends ElementInterface
{
    protected $type;

    public function __construct($type, $label = '')
    {
        $this->type = $type;
        $this->label = $label;
    }

    public function render()
    {
        return sprintf(
            '<input type="%s" value="%s" %s />',
            $this->type,
            htmlspecialchars($this->value, ENT_QUOTES),
            $this->attributes()
        );
    }
}