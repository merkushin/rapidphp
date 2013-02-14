<?php

/**
 * @package Rapid
 * @author Dmitry Merkushin <merkushin@gmail.com>
 */

namespace Rapid\Form\Validator;

class Required extends \Rapid\Form\Validator
{
    public function isValid(array $data)
    {
        try {
            $value = $this->getValue($data);
        } catch (\Rapid\Form\Validator\Exception\DoesntExist $e) {
            return false;
        }

        if (empty($value)) {
            return false;
        }

        return true;
    }

    public function getError()
    {
        return sprintf('%s is required', $this->elementName);
    }
}