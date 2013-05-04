<?php
use Rapid\Form;

class FormTest extends PHPUnit_Framework_TestCase
{
    public function testCreateForm()
    {
        $form = new Form();
        $this->assertInstanceOf('Rapid\Form', $form);
    }

    protected function getModelMock()
    {
        $model = $this->getMockBuilder('Rapid\Model')->getMock();
        return $model;
    }

    protected function getElementMock()
    {
        $element = $this->getMockBuilder('Rapid\Form\Element\Input')
            ->disableOriginalConstructor()
            ->getMock();

        $element->expects($this->at(0))
            ->method('setAttribute')
            ->with('name', 'input');

        $element->expects($this->at(1))
            ->method('setAttribute')
            ->with('id', 'input_id');

        return $element;
    }

    public function testAddElement()
    {
        $form = new Form();

        $element = $this->getElementMock();

        $form->addElement('input', $element);
        $this->assertInstanceOf('Rapid\Form\Element\Input', $form->element('input'));
    }

    public function testPopulate()
    {
        $form = new Form();

        $element = $this->getElementMock();
        $element->expects($this->once())
            ->method('setValue')
            ->with('test-value');

        $form->addElement('input', $element);

        $array = array(
            'input' => 'test-value',
        );

        $this->assertInstanceOf('Rapid\Form', $form->populate($array));
    }

    public function testGetErrors()
    {
        $form = new Form();

        $expected = array(
            'input' => array('My error'),
        );

        $element = $this->getElementMock();
        $element->expects($this->once())
            ->method('errors')
            ->will($this->returnValue($expected['input']));

        $form->addElement('input', $element);

        $actual = $form->errors();

        $this->assertEquals($expected, $actual);
    }
}