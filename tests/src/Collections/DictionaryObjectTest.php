<?php
namespace Yukar\Linq\Tests\Collections;

use Yukar\Linq\Collections\DictionaryObject;
use Yukar\Linq\Collections\KeyValuePair;

class DictionaryObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testAppend()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->append(new KeyValuePair("key", "value"));

        $result = $dic_obj->getArrayCopy();

        $this->assertCount(1, $result);
        $this->assertInstanceOf('Yukar\Linq\Collections\KeyValuePair', $result[0]);
    }

    public function testOffsetSet()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->offsetSet("key", "value");

        $result = $dic_obj->getArrayCopy();

        $this->assertCount(1, $result);
        $this->assertInstanceOf('Yukar\Linq\Collections\KeyValuePair', $result[0]);
    }

    public function testOffsetGet()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->append(new KeyValuePair("key", "value"));

        $result = $dic_obj->offsetGet("key");

        $this->assertEquals("value", $result);
    }

    public function testOffsetExists()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->offsetSet("key", "value");

        $this->assertTrue($dic_obj->offsetExists("key"));
        $this->assertFalse($dic_obj->offsetExists("key2"));
    }

    public function testOffsetUnset()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->append(new KeyValuePair("key", "value"));
        $dic_obj->offsetUnset("key");

        $this->setExpectedException('\OutOfBoundsException');
        $dic_obj->offsetGet("key");
    }

    public function testTwiceAppend()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->append(new KeyValuePair("key", "value"));
        $dic_obj->offsetSet("key2", "value2");

        $result = $dic_obj->getDictionaryCopy();

        $this->assertCount(2, $result);
        $this->assertEquals([ "key" => "value", "key2" => "value2" ], $result);
    }

    public function testTwiceAppendFailed()
    {
        $dic_obj = new DictionaryObject();
        $dic_obj->offsetSet(1, "value2");

        $this->setExpectedException('\UnexpectedValueException');
        $dic_obj->append(new KeyValuePair("key", "value"));
    }
}
