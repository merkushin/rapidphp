<?php

namespace Rapid\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        /**
         * I don't know how to test sessions, 'cause i got message:
         * session_start(): Cannot send session cookie - headers already sent by (output started at /usr/local/zend/share/pear/PHPUnit/Util/Printer.php:172)
         * PHPUnit do output himself... :(
         */
    }

    public function testSessionStart()
    {
        return;
        $id = \Rapid\Session::sessionId();
        $this->assertEquals('', $id);

        \Rapid\Session::name('name');
        \Rapid\Session::start();
        $id = \Rapid\Session::sessionId();
        $this->assertNotEmpty($id);
    }

    public function testRegenerateId()
    {
        return;
        session_destroy();
        \Rapid\Session::name('name');
        \Rapid\Session::start();
        $id = \Rapid\Session::sessionId();
        $this->assertNotEmpty($id);
        \Rapid\Session::regenerateId();
        \Rapid\Session::regenerateId();
        $newId = \Rapid\Session::sessionId();
        $this->assertNotEquals($id, $newId);
    }
}