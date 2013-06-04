<?php

class TestableElement extends \Rapid\Form\Element
{
    public function render()
    {
        return '<element />';
    }
}

class ElementTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rapid\Form\Element
     */
    protected $element;

    public function setUp()
    {
        $this->element = new \TestableElement();
    }

    public function testCreate()
    {
        $this->assertInstanceOf('Rapid\Form\Element', $this->element);
    }

    public function testRender()
    {
        $this->assertEquals('<element />', $this->element->render());
    }

    public function testSetValue()
    {
        $this->assertNull($this->element->value());
        $this->assertInstanceOf('Rapid\Form\Element', $this->element->setValue(1));
        $this->assertEquals(1, $this->element->value());
    }

    public function testSetAttribute()
    {
        $this->assertInstanceOf('Rapid\Form\Element', $this->element->setAttribute('data', '1'));
    }

    public function testSetLabel()
    {
        $this->element->setLabel('My Label');
        $this->assertEquals('<label>My Label</label>', $this->element->label());
        $this->element->setAttribute('id', 'xxx');
        $this->assertEquals('<label for="xxx">My Label</label>', $this->element->label());
    }

    public function testSetErrors()
    {
        $errors = array();
        $this->element->setErrors($errors);
        $this->assertEquals($errors, $this->element->errors());
        $errors[] = 'Test';
        $this->element->setErrors($errors);
        $this->assertEquals($errors, $this->element->errors());
        $this->element->addError('Test 2');
        $errors[] = 'Test 2';
        $this->assertEquals($errors, $this->element->errors());
    }

    public function testSetValidators()
    {
        $validator = $this->getMockBuilder('Rapid\Form\Validator\Required')->getMock();
        $this->assertEquals(array(), $this->element->validators());
        $validators = array();
        $this->element->setValidators($validators);
        $this->assertEquals($validators, $this->element->validators());
        $validators[] = clone $validator;
        $this->element->setValidators($validators);
        $this->assertEquals($validators, $this->element->validators());
        $validator2 = clone $validator;
        $this->element->addValidator($validator2);
        $validators[] = $validator2;
        $this->assertEquals($validators, $this->element->validators());
    }
}