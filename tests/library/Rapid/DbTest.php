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