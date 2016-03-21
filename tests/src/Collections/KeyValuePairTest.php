<?php
namespace Yukar\Linq\Tests\Collections;

use Yukar\Linq\Collections\KeyValuePair;

class KeyValuePairTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKey()
    {
        $pair = new KeyValuePair("key", "value");

        $this->assertEquals("key", $pair->getKey());
    }

    public function testGetValue()
    {
        $pair = new KeyValuePair("key", "value");

        $this->assertEquals("value", $pair->getValue());
    }

    public function testToString()
    {
        $pair = new KeyValuePair("key", "value");

        $this->assertEquals("value", (string)$pair);
    }
}
