<?php
namespace Yukar\Linq\Tests\Collections;

class BaseCommonCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testClear()
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCommonCollection');
        $object = (new \ReflectionClass($mock))->newInstance([ 'str', 1, true, new \stdClass(), null ]);
        $reflector = new \ReflectionClass($object);

        $reflector->getMethod('clear')->invoke($object);

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEmpty($method_reflector->invoke($object)->getArrayCopy());
    }

    public function testGetSize()
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCommonCollection');
        $object = (new \ReflectionClass($mock))->newInstance([ 'str', 1, true, new \stdClass(), null ]);
        $reflector = new \ReflectionClass($object);

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame(
            $method_reflector->invoke($object)->count(),
            $reflector->getMethod('getSize')->invoke($object)
        );
    }

    public function testIsReadOnly()
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCommonCollection');
        $object = (new \ReflectionClass($mock))->newInstance([ 'str', 1, true, new \stdClass(), null ]);
        $reflector = new \ReflectionClass($object);

        $this->assertFalse($reflector->getMethod('isReadOnly')->invoke($object));
    }
}
