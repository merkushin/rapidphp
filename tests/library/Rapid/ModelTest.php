<?php

use Rapid\Model;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $model = new \Rapid\Model();
        $this->assertInstanceOf('\Rapid\Model', $model);
    }

    /**
     * @depends testCreate
     */
    public function testIdMethods()
    {
        $model = new \Rapid\Model();
        $this->assertEquals(null, $model->id(), 'id() of new model is not null');
        $model->setId(1);
        $this->assertEquals(1, $model->id(), 'id don\t match');
    }

    /**
     * @depends testCreate
     */
    public function testSetProperties()
    {
        $model = new \Rapid\Model();
        $this->assertEquals(array(), $model->properties(), 'Properties of new model are not empty');

        $properties = array('my' => 1, 'another' => 'string');
        $model->setProperties($properties, true);
        $this->assertEquals($properties, $model->properties(), 'Properties don\t match');
        $this->assertEquals(array(), $model->modifiedProperties(), 'Modified properties are incorrect');

        $modifiedProperties = array('my' => 2);
        $model->setProperties($modifiedProperties);
        $this->assertEquals(
            array_merge($properties, $modifiedProperties),
            $model->properties(),
            'Properties don\t match'
        );
        $this->assertEquals($modifiedProperties, $model->modifiedProperties(), 'Modified properties are incorrect');

        $model->setProperty('new', 1);
        $this->assertEquals(1, $model->property('new'), 'Property not set');
        $this->assertEquals(
            array_merge($modifiedProperties, array('new' => 1)),
            $model->modifiedProperties(),
            'Modified properties are incorrect'
        );
    }

    /**
     * @depends testCreate
     */
    public function testMagicMethods()
    {
        $model = new \Rapid\Model();
        $model->new_var = 1;
        $this->assertEquals(1, $model->new_var);
    }
}