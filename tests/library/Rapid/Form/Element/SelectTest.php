<?php

class SelectTest extends PHPUnit_Framework_TestCase
{
    public function testEmptySelect()
    {
        $select = new \Rapid\Form\Element\Select(array(), 'Select');
        $this->assertInstanceOf('\Rapid\Form\Element\Select', $select);
        $this->assertEquals('<select ></select>', $select->render());
    }

    /**
     * @depends testEmptySelect
     */
    public function testEmptySelectWithAttr()
    {
        $select = new \Rapid\Form\Element\Select(array(), 'Select');
        $select->setAttribute('name', 'foo');
        $this->assertEquals('<select name="foo"></select>', $select->render());
    }

    /**
     * @depends testEmptySelect
     */
    public function testSelectWithOptions()
    {
        $select = new \Rapid\Form\Element\Select(array('val1' => 'Value #1'), 'Select');
        $select->setAttribute('name', 'foo');
        $this->assertEquals('<select name="foo"><option value="val1" >Value #1</option></select>', $select->render());
    }

    /**
     * @depends testEmptySelect
     */
    public function testSelectWithSelectedOption()
    {
        $select = new \Rapid\Form\Element\Select(array('val1' => 'Value #1', 'val2' => 'Value #2'), 'Select');
        $select->setAttribute('name', 'foo');
        $select->setValue('val2');
        $this->assertEquals('<select name="foo"><option value="val1" >Value #1</option><option value="val2" selected="selected">Value #2</option></select>', $select->render());
    }
}