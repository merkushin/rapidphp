<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Form;

class Text extends \Rapid\Form\ElementInterface
{
    public function __construct($label = '')
    {
        $this->label = $label;
    }

    public function render()
    {
        return sprintf(
            '<textarea %s>%s</textarea>',
            $this->attributes(),
            htmlspecialchars($this->value(), ENT_QUOTES)
        );
    }


}