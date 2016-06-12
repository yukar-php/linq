<?php
namespace Yukar\Linq\Tests\Collections;

class BaseCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIterator()
    {
        $base_list = [ 1, 2, 3, 4, 5 ];
        $target_index = 2;

        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCollection');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $iterator = $reflector->getMethod('getIterator')->invoke($object);
        $iterator[$target_index] = 30;

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);
        $current_source_list = $method_reflector->invoke($object)->getArrayCopy();

        $this->assertNotSame($current_source_list[$target_index], $iterator[$target_index]);
        $this->assertSame($base_list[$target_index], $current_source_list[$target_index]);
    }

    public function providerAdd()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            // 正常系（文字列）
            [ [ 'string' ], 'string', [] ],
            [ [ 'before', 'next' ], 'next', [ 'before' ] ],
            // 正常系（数値）
            [ [ 1 ], 1, [] ],
            [ [ 1, 3, 5, 7 ], 7, [ 1, 3, 5 ] ],
            // 正常系（論理型）
            [ [ false ], false, [] ],
            [ [ true, false ], false, [ true ] ],
            // 正常系（オブジェクト）
            [ [ $object ], $object, [] ],
            // 正常系（クロージャ）
            [ [ $closure ], $closure, [] ],
            // 正常系（配列型）
            [ [ [ 'a', 2, 'c' ] ], [ 'a', 2, 'c' ], [] ],
            [ [ $object, [ $closure, 1 ] ], [ $closure, 1 ], [ $object ] ],
            // 正常系（NULL）
            [ [ null ], null, [] ],
            [ [ false, 1, 'str', null ], null, [ false, 1, 'str' ] ],
        ];
    }

    /**
     * @dataProvider providerAdd
     */
    public function testAdd($expected, $value, $base_list)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCollection');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $reflector->getMethod('add')->invoke($object, $value);

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame($expected, $method_reflector->invoke($object)->getArrayCopy());
    }

    public function providerContainsItem()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ [ 1, false, $object, null, $closure, 'str' ], 1, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], 0, false ],
            [ [ 1, false, $object, null, $closure, 'str' ], false, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], true, false ],
            [ [ 1, false, $object, null, $closure, 'str' ], $object, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], null, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], $closure, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], 'str', true ],
            [ [ 1, false, $object, null, $closure, 'str' ], 'text', false ],
        ];
    }

    /**
     * @dataProvider providerContainsItem
     */
    public function testContainsItem($base_list, $item, $expected)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCollection');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $this->assertSame($expected, $reflector->getMethod('containsItem')->invoke($object, $item));
    }

    public function providerCopyTo()
    {
        return [
            [ [ 1, 2, 3 ], [ 1, 2, 3 ], [], null, 0 ],
            [ [ 'c', 'a' ], [ 'b', 'c', 'a' ], [], null, 1 ],
            [ [ 1, 'str' ], [ true, 1, 'str', null ], [], 2, 1 ]
        ];
    }

    /**
     * @dataProvider providerCopyTo
     */
    public function testCopyTo($expected, $base_list, $dst, $copy_count, $copy_start)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCollection');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $reflector->getMethod('copyTo')->invokeArgs($object, [ &$dst, $copy_count, $copy_start ]);

        $this->assertSame($expected, $dst);
    }

    public function providerRemove()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ [ 1, false, $object, null, $closure, 'str' ], 1, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], 0, false ],
            [ [ 1, false, $object, null, $closure, 'str' ], false, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], true, false ],
            [ [ 1, false, $object, null, $closure, 'str' ], $object, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], null, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], $closure, true ],
            [ [ 1, false, $object, null, $closure, 'str' ], 'str', true ],
            [ [ 1, false, $object, null, $closure, 'str' ], 'text', false ],
        ];
    }

    /**
     * @dataProvider providerRemove
     */
    public function testRemove($base_list, $item, $expected)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseCollection');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $this->assertSame($expected, $reflector->getMethod('remove')->invoke($object, $item));

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame(
            count($base_list),
            $method_reflector->invoke($object)->count() + ($expected === true ? 1 : 0)
        );
    }
}
