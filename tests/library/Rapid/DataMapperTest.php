<?php

use \Rapid\DataMapper;

class DataMapperTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $options = array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => 'test',
        );
        $mysql = \Rapid\Db::factory($options);
    }

    public function testCreate()
    {
        $mapper = new \Rapid\DataMapper();
        $this->assertInstanceOf('\Rapid\DataMapper', $mapper);
    }

    /**
     * @depends testCreate
     */
    public function testTablename()
    {
        $this->createTestTable();


        $mapper = new DataMapper();
        $mapper->setTablename('my_table');
        $this->assertEquals('my_table', $mapper->tablename());

        $this->dropTestTable();
    }

    /**
     * @depends testTablename
     */
    public function testModelClass()
    {
        $mapper = new \Rapid\DataMapper();
        $mapper->setModelClass('MyModel');
        $this->assertEquals('MyModel', $mapper->modelClass());
    }

    /**
     * @depends testModelClass
     */
    public function testSaveAndFind()
    {
        $model = new \Rapid\Model();
        $model->string = 'abc';

        $this->assertEquals(null, $model->id());
        $this->assertEquals('abc', $model->string);

        $this->createTestTable();
        $mapper = new \Rapid\DataMapper();
        $mapper->setTablename('my_table')
            ->setModelClass('\Rapid\Model');
        $mapper->save($model);

        $this->assertEquals(1, $model->id());
        $this->assertEquals('abc', $model->string);

        $model->string = 'qwerty';
        $mapper->save($model);

        $this->assertEquals(1, $model->id());
        $this->assertEquals('qwerty', $model->string);

        $fetchedModel = $mapper->find(1);
        $this->assertEquals(1, $fetchedModel->id());
        $this->assertEquals('qwerty', $fetchedModel->string);

        $this->dropTestTable();
    }

    /**
     * @depends testSaveAndFind
     */
    public function testDeleteAndFetchAll()
    {
        $this->createTestTable();

        $mapper = new \Rapid\DataMapper();
        $mapper->setTablename('my_table')
            ->setModelClass('\Rapid\Model');


        $model = new \Rapid\Model();
        $model->string = 'abc';
        $mapper->save($model);
        $models = $mapper->fetchAll();
        $this->assertEquals(1, count($models));

        $model2 = new \Rapid\Model();
        $model2->string = 'cba';
        $mapper->save($model2);
        $models = $mapper->fetchAll();
        $this->assertEquals(2, count($models));

        $mapper->delete($model);
        $models = $mapper->fetchAll();
        $this->assertEquals(1, count($models));

        $mapper->delete($model2);
        $models = $mapper->fetchAll();
        $this->assertEquals(0, count($models));

        $this->dropTestTable();
    }

    protected function createTestTable()
    {
        $db = \Rapid\Db::get();
        $query = 'CREATE TABLE my_table (id int primary key auto_increment, string varchar(255) not null default "")';
        $db->query($query);
    }

    protected function dropTestTable()
    {
        $db = \Rapid\Db::get();
        $db->query('DROP TABLE my_table');
    }
}