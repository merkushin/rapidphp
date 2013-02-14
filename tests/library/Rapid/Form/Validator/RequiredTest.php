<?php

use \Rapid\Form\Validator;

class RequiredTest extends PHPUnit_Framework_TestCase
{
    public function testCreateValidator()
    {
        $validator = new Validator\Required();
        $this->assertInstanceOf('\Rapid\Form\Validator\Required', $validator);
    }

    public function testIsValid()
    {
        $validator = new Validator\Required();
        $validator->setElementName('name');
        $data = array();
        $this->assertFalse($validator->isValid($data), 'Should be false because is not set');
        $data = array('name' => '');
        $this->assertFalse($validator->isValid($data), 'Should be false because has no value');
        $this->assertEquals('name is required', $validator->getError());
        $data = array('name' => 'Test');
        $this->assertTrue($validator->isValid($data), 'Should be true');
    }

    public function testIsValidWithModel()
    {
        $model = new \Rapid\Model();
        $validator = new Validator\Required();
        $validator->setModel($model);
        $validator->setElementName('name');
        $data = array();
        $this->assertFalse($validator->isValid($data));
        $data = array('name' => '');
        $this->assertFalse($validator->isValid($data));
        $data = array('name' => 'Test');
        $this->assertFalse($validator->isValid($data));
        $data = array(
            'model' => array(
                'name' => '',
            ),
        );
        $this->assertFalse($validator->isValid($data));
        $this->assertEquals('name is required', $validator->getError());
        $data = array(
            'model' => array(
                'name' => 'Test',
            ),
        );
        $this->assertTrue($validator->isValid($data));
    }
}