<?php

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testCreateConfig()
    {
        $config = new \Rapid\Config();
        $this->assertInstanceOf('\Rapid\Config', $config);
    }

    public function testGetNull()
    {
        $config = new \Rapid\Config();
        $this->assertEquals(null, $config->get('key'), 'There is no value for "key"');
    }

    public function testCreateFromArray()
    {
        $options = array(
            'key' => 'value',
        );
        $config = new \Rapid\Config($options);

        $this->assertEquals('value', $config->get('key'));
        $this->assertEquals('value', $config->key);
        $this->assertEquals($options, $config->options());
    }

    public function testNestedOptions()
    {
        $options = array(
            'key' => 'value',
            'nested' => array(
                'key1' => 'value1',
            ),
        );
        $config = new \Rapid\Config($options);

        $this->assertEquals('value', $config->get('key'));
        $this->assertEquals('value', $config->key);
        $this->assertEquals($options, $config->options());

        $this->assertInstanceOf('\Rapid\Config', $config->get('nested'));
        $this->assertInstanceOf('\Rapid\Config', $config->nested);

        $this->assertEquals($options['nested'], $config->nested->options());

        $this->assertEquals('value1', $config->nested->key1);
    }

    public function testMerge()
    {
        $options1 = array();
        for ($i = 0; $i < 5; $i++)
        {
            $options1['key_' . $i] = 'value_' . $i;
        }

        $options2 = array();
        for ($i = 5; $i < 10; $i++)
        {
            $options2['key_' . $i] = 'value_' . $i;
        }

        $test1Config = new \Rapid\Config($options1);
        for ($i = 0; $i < 10; $i++)
        {
            if ($i < 5)
            {
                $this->assertArrayHasKey('key_' . $i, $test1Config->options());
            }
            else
            {
                $this->assertArrayNotHasKey('key_' . $i, $test1Config->options());
            }
        }
        $test1Config->merge($options2);
        for ($i = 0; $i < 10; $i++)
        {
            $key = 'key_' . $i;
            $this->assertArrayHasKey($key, $test1Config->options());
            $this->assertEquals('value_'.$i, $test1Config->get($key));
        }

        $test2Config = new \Rapid\Config($options1);
        for ($i = 0; $i < 10; $i++)
        {
            if ($i < 5)
            {
                $this->assertArrayHasKey('key_' . $i, $test2Config->options());
            }
            else
            {
                $this->assertArrayNotHasKey('key_' . $i, $test2Config->options());
            }
        }
        $test2Config->merge( new \Rapid\Config($options2) );
        for ($i = 0; $i < 10; $i++)
        {
            $key = 'key_' . $i;
            $this->assertArrayHasKey($key, $test2Config->options());
            $this->assertEquals('value_'.$i, $test2Config->get($key));
        }
    }

    /**
     * @expectedException \Rapid\Config\Exception
     */
    public function testLoadNotExistedFileError()
    {
        new \Rapid\Config('sadasd.php');
    }


    /**
     * @expectedException \Rapid\Config\Exception\Unsupported
     */
    public function testLoadUnsupportedFileError()
    {
        new \Rapid\Config(__DIR__ . '/../../../phpunit.xml');
    }
}