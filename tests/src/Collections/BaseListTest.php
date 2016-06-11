<?php
namespace Yukar\Linq\Tests\Collections;

class BaseListTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccess()
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseList');
        $object = (new \ReflectionClass($mock))->newInstance();
        $object[] = 1;

        $method_reflector = (new \ReflectionClass($object))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame($method_reflector->invoke($object)->getArrayCopy()[0], $object[0]);

        unset($object[0]);

        $this->assertFalse(isset($object[0]));
    }

    public function providerIndexOf()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ 3, [ 1, false, $object, null, $closure, 'str' ], null, 0, null ],
            [ 1, [ 1, false, $object, null, $closure, 'str' ], false, 0, 3 ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], $closure, 0, 3 ],
            [ 2, [ 1, false, $object, null, $closure, 'str' ], $object, 2, 3 ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], 'str', 2, 3 ],
            [ 3, [ 1, false, $object, null, $closure, 'str' ], null, 1, 3 ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], 1, 1, 3 ],
        ];
    }

    /**
     * @dataProvider providerIndexOf
     */
    public function testIndexOf($expected, $base_list, $item, $index, $count)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseList');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $this->assertSame($expected, $reflector->getMethod('indexOf')->invoke($object, $item, $index, $count));
    }

    public function providerIndexOfFailure()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ '\OutOfRangeException', [ 1, false, $object, null, $closure, 'str' ], 0, -1, 1 ],
            [ '\OutOfRangeException', [ 1, false, $object, null, $closure, 'str' ], 0, 6, 1 ],
            [ '\OutOfRangeException', [ 1, false, $object, null, $closure, 'str' ], 0, 0, 0 ],
            [ '\OutOfRangeException', [ 1, false, $object, null, $closure, 'str' ], 0, 0, 7 ],
            [ '\OutOfRangeException', [ 1, false, $object, null, $closure, 'str' ], 0, -1, 0 ],
            [ '\OutOfRangeException', [ 1, false, $object, null, $closure, 'str' ], 0, 6, 7 ],
        ];
    }

    /**
     * @dataProvider providerIndexOfFailure
     */
    public function testIndexOfFailure($expected, $base_list, $item, $index, $count)
    {
        $this->setExpectedException($expected);

        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseList');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $this->assertSame($expected, $reflector->getMethod('indexOf')->invoke($object, $item, $index, $count));
    }

    public function providerInsert()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            // 正常系（文字列）
            [ [ 'string' ], 0, 'string', [] ],
            [ [ 'before', 'next' ], 1, 'next', [ 'before' ] ],
            // 正常系（数値）
            [ [ 1 ], 0, 1, [] ],
            [ [ 1, 3, 5, 7 ], 1, 3, [ 1, 5, 7 ] ],
            // 正常系（論理型）
            [ [ false ], 0, false, [] ],
            [ [ true, false ], 1, false, [ true ] ],
            // 正常系（オブジェクト）
            [ [ $object ], 0, $object, [] ],
            // 正常系（クロージャ）
            [ [ $closure ], 0, $closure, [] ],
            // 正常系（配列型）
            [ [ [ 'a', 2, 'c' ] ], 0, [ 'a', 2, 'c' ], [] ],
            [ [ $object, [ $closure, 1 ] ], 1, [ $closure, 1 ], [ $object ] ],
            // 正常系（NULL）
            [ [ null ], 0, null, [] ],
            [ [ false, 1, null, 'str' ], 2, null, [ false, 1, 'str' ] ],
        ];
    }

    /**
     * @dataProvider providerInsert
     */
    public function testInsert($expected, $index, $value, $base_list)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseList');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $reflector->getMethod('insert')->invoke($object, $index, $value);

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame($expected, $method_reflector->invoke($object)->getArrayCopy());
    }

    public function providerInsertFailure()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            // インデックス位置が不正（負数）
            [ -1, 'str', [], '\OutOfRangeException' ],
            [ -2, true, [ 'str' ], '\OutOfRangeException' ],
            [ -10, $closure, [ 'str', true ], '\OutOfRangeException' ],
            // インデックス位置が不正（要素数越え）
            [ 1, 1, [], '\OutOfRangeException' ],
            [ 2, $object, [ 1 ], '\OutOfRangeException' ],
            [ 10, null, [ 1, $object ], '\OutOfRangeException' ],
        ];
    }

    /**
     * @dataProvider providerInsertFailure
     */
    public function testInsertFailure($index, $value, $base_list, $expectedException)
    {
        $this->setExpectedException($expectedException);

        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseList');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $reflector->getMethod('insert')->invoke($object, $index, $value);
    }

    public function providerRemoveAt()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ [ 1, false, $object, $closure, 'str' ], 0, [ false, $object, $closure, 'str' ],null ],
            [ [ 1, false, $object, $closure, 'str' ], 1, [ 1, $object, $closure, 'str' ],null ],
            [ [ 1, false, $object, $closure, 'str' ], 2, [ 1, false, $closure, 'str' ], null ],
            [ [ 1, false, $object, $closure, 'str' ], 3, [ 1, false, $object, 'str' ], null ],
            [ [ 1, false, $object, $closure, 'str' ], 4, [ 1, false, $object, $closure ], null ],
            [ [ 1, false, $object, $closure, 'str' ], 5, [], '\OutOfRangeException' ],
            [ [ 1, false, $object, $closure, 'str' ], -1, [], '\OutOfRangeException' ],
        ];
    }

    /**
     * @dataProvider providerRemoveAt
     */
    public function testRemoveAt($base_list, $index, $expected, $expectedException)
    {
        isset($expectedException) && $this->setExpectedException($expectedException);

        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseList');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $reflector->getMethod('removeAt')->invoke($object, $index);

        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($object)->getArrayCopy());
    }
}
