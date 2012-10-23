<?php

use \Rapid\Db;

class DbTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testFactory()
    {
        $options = array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => 'test',
        );
        $mysql = \Rapid\Db::factory($options);
        $this->assertInstanceOf('\Rapid\Db\MySQL', $mysql);
    }

    public function testGet()
    {
        $this->assertEquals(null, \Rapid\Db::get('unknown'));
    }

    /**
     * @depends testFactory
     */
    public function testQuery()
    {
        $db = \Rapid\Db::get();
        $query = 'CREATE TABLE my_table (id int, string varchar(255))';
        $this->assertEquals(0, $db->query($query));
        $this->dropTestTable();
    }

    /**
     * @depends testQuery
     */
    public function testQueryWithPreparedStatement()
    {
        $this->createTestTable(false);
        $db = \Rapid\Db::get();
        for ($i = 1; $i <= 10; $i++)
        {
            $q = 'INSERT INTO my_table (id, string) VALUES(:id, :string)';
            $params = array(
                'id' => $i,
                'string' => 'string-' . $i,
            );
            $ret = $db->query($q, $params);
            $this->assertEquals(1, $ret, 'Insertion error #' . $i);
        }
        $this->dropTestTable();
    }

    /**
     * @depends testQueryWithPreparedStatement
     */
    public function testFetchAll()
    {
        $this->createTestTable();
        $db = \Rapid\Db::get();
        $expectedRet = array();
        for ($i = 1; $i <= 10; $i++)
        {
            $expectedRet[] = array(
                'id' => $i,
                'string' => 'string-' . $i,
            );
        }

        $ret = $db->fetchAll('SELECT * FROM my_table');
        $this->assertEquals($expectedRet, $ret, 'Returning value don\'t match expected value');
        $this->dropTestTable();
    }

    /**
     * @depends testQueryWithPreparedStatement
     */
    public function testFetchRow()
    {
        $this->createTestTable();
        $db = \Rapid\Db::get();

        $ret = $db->fetchRow('SELECT * FROM my_table');
        $this->assertEquals(array('id' => 1, 'string' => 'string-1'), $ret, 'Returning value don\'t match expected value');
        $this->dropTestTable();
    }


    public function testFetchColumn()
    {
        $this->createTestTable();
        $db = \Rapid\Db::get();

        $expectedRet = array();
        for ($i = 1; $i <= 10; $i++)
        {
            $expectedRet[] = $i;
        }

        $ret = $db->fetchColumn('SELECT * FROM my_table');
        $this->assertEquals($expectedRet, $ret, 'Returning value don\'t match expected value');
        $this->dropTestTable();
    }

    /**
     * @depends testQueryWithPreparedStatement
     */
    public function testFetchOne()
    {
        $this->createTestTable();
        $db = \Rapid\Db::get();

        $ret = $db->fetchOne('SELECT * FROM my_table');
        $this->assertEquals(1, $ret, 'Returning value don\'t match expected value');
        $this->dropTestTable();
    }

    /**
     * @depends testQueryWithPreparedStatement
     */
    public function testInsert()
    {
        $this->createTestTable(false);
        $db = \Rapid\Db::get();

        $db->insert('my_table', array('id' => 1, 'string' => 'abc'));

        $rows = $db->fetchAll('SELECT * FROM my_table');

        $this->assertEquals(1, count($rows), 'Invalid number of rows');

        $this->assertEquals(1, $rows[0]['id'], 'Invalid value of id field');
        $this->assertEquals('abc', $rows[0]['string'], 'Invalid value of id field');
        $this->dropTestTable();
    }

    /**
     * @depends testInsert
     */
    public function testUpdate()
    {
        $this->createTestTable(false);
        $db = \Rapid\Db::get();

        $db->insert('my_table', array('id' => 1, 'string' => 'abc'));
        $db->insert('my_table', array('id' => 2, 'string' => 'abc'));

        $rows = $db->fetchAll('SELECT * FROM my_table');

        $this->assertEquals(2, count($rows), 'Invalid number of rows');
        $this->assertEquals(1, $rows[0]['id'], 'Invalid value of id field');
        $this->assertEquals('abc', $rows[0]['string'], 'Invalid value of id field');
        $this->assertEquals(2, $rows[1]['id'], 'Invalid value of id field');
        $this->assertEquals('abc', $rows[1]['string'], 'Invalid value of id field');

        $db->update('my_table', array('string' => 'cba')); // update all rows

        $rows = $db->fetchAll('SELECT * FROM my_table');

        $this->assertEquals(2, count($rows), 'Invalid number of rows');
        $this->assertEquals(1, $rows[0]['id'], 'Invalid value of id field');
        $this->assertEquals('cba', $rows[0]['string'], 'Invalid value of id field');
        $this->assertEquals(2, $rows[1]['id'], 'Invalid value of id field');
        $this->assertEquals('cba', $rows[1]['string'], 'Invalid value of id field');

        $db->update('my_table', array('string' => 'abc'), array('id' => 1)); // update row with id=1

        $rows = $db->fetchAll('SELECT * FROM my_table');

        $this->assertEquals(2, count($rows), 'Invalid number of rows');
        $this->assertEquals(1, $rows[0]['id'], 'Invalid value of id field');
        $this->assertEquals('abc', $rows[0]['string'], 'Invalid value of id field');
        $this->assertEquals(2, $rows[1]['id'], 'Invalid value of id field');
        $this->assertEquals('cba', $rows[1]['string'], 'Invalid value of id field');

        $this->dropTestTable();
    }

    public function testDelete()
    {
        $this->createTestTable(false);
        $db = \Rapid\Db::get();

        $db->insert('my_table', array('id' => 1, 'string' => 'abc'));
        $db->insert('my_table', array('id' => 2, 'string' => 'bca'));
        $db->insert('my_table', array('id' => 3, 'string' => 'cab'));

        $rows = $db->fetchAll('SELECT * FROM my_table');
        $this->assertEquals(3, count($rows), 'Invalid number of rows');

        $db->delete('my_table', array('id' => 2)); // delete row with id=2

        $rows = $db->fetchAll('SELECT * FROM my_table');
        $this->assertEquals(2, count($rows), 'Invalid number of rows');

        $db->delete('my_table'); // delete all rows
        $rows = $db->fetchAll('SELECT * FROM my_table');
        $this->assertEquals(0, count($rows), 'Invalid number of rows');

        $this->dropTestTable();
    }

    public function testPrepareWhere()
    {
        $db = \Rapid\Db::get();

        $ret = $db->prepareWhere(array('id' => 1));
        $this->assertEquals(
            array(
                ' WHERE `id`=:id',
                array(
                    'id' => 1,
                ),
            ),
            $ret,
            'Return array is invalid'
        );

        $ret = $db->prepareWhere(array('id' => 1, 'x' => 'str'));
        $this->assertEquals(
            array(
                ' WHERE `id`=:id AND `x`=:x',
                array(
                    'id' => 1,
                    'x' => 'str'
                ),
            ),
            $ret,
            'Return array is invalid'
        );
    }

    public function tearDown()
    {
        $db = \Rapid\Db::get();
        if ($db instanceof \Rapid\Db)
        {
            $db->query('DROP TABLE my_table');
        }
    }

    protected function createTestTable($withRows = true)
    {
        $db = \Rapid\Db::get();
        $query = 'CREATE TABLE my_table (id int, string varchar(255))';
        $db->query($query);
        if ($withRows)
        {
            for ($i = 1; $i <= 10; $i++)
            {
                $q = 'INSERT INTO my_table (id, string) VALUES(:id, :string)';
                $params = array(
                    'id' => $i,
                    'string' => 'string-' . $i,
                );
                $ret = $db->query($q, $params);
                $this->assertEquals(1, $ret, 'Insertion error #' . $i);
            }
        }
    }

    protected function dropTestTable()
    {
        $db = \Rapid\Db::get();
        $db->query('DROP TABLE my_table');
    }
}