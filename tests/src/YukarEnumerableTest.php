<?php
namespace Yukar\Linq\Tests\Enumerable;

use Yukar\Linq\YukarEnumerable;

class YukarEnumerableTest extends \PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $actual = YukarEnumerable::new(new \ArrayObject([ 1, 3, 5 ]));
        
        $this->assertInstanceOf('Yukar\Linq\YukarLinq', $actual);
        $this->assertEquals(new \ArrayObject([ 1, 3, 5 ]), $actual->getSourceList());
        $this->assertCount(3, $actual->getSourceList());
    }
    
    public function testFrom()
    {
        $actual = YukarEnumerable::from([ 1, 2, 3, 4 ]);
        
        $this->assertInstanceOf('Yukar\Linq\YukarLinq', $actual);
        $this->assertEquals(new \ArrayObject([ 1, 2, 3, 4 ]), $actual->getSourceList());
        $this->assertCount(4, $actual->getSourceList());
    }
    
    public function testEmpty()
    {
        $actual = YukarEnumerable::empty();
        
        $this->assertInstanceOf('Yukar\Linq\YukarLinq', $actual);
        $this->assertEquals(new \ArrayObject([]), $actual->getSourceList());
        $this->assertCount(0, $actual->getSourceList());
    }
    
    public function testRange()
    {
        $actual = YukarEnumerable::range(1, 5);
        
        $this->assertInstanceOf('Yukar\Linq\YukarLinq', $actual);
        $this->assertEquals(new \ArrayObject([ 1, 2, 3, 4, 5 ]), $actual->getSourceList());
        $this->assertCount(5, $actual->getSourceList());
    }
    
    public function testRepeat()
    {
        $actual = YukarEnumerable::repeat(1, 3);
        
        $this->assertInstanceOf('Yukar\Linq\YukarLinq', $actual);
        $this->assertEquals(new \ArrayObject([ 1, 1, 1 ]), $actual->getSourceList());
        $this->assertCount(3, $actual->getSourceList());
    }
}
