<?php
namespace Yukar\Linq\Tests\Collections;

use Yukar\Linq\Collections\ListObject;

class ListObjectTest extends \PHPUnit_Framework_TestCase
{
    public function providerAddRange()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            // 正常系（文字列）
            [ [ 'alpha', 'beta' ], [ 'alpha', 'beta' ], [], null ],
            [ [ 'before', 'next' ], [ 'next' ], [ 'before' ], null ],
            // 正常系（数値）
            [ [ 2, 4 ], [ 2, 4 ], [], null ],
            [ [ 1, 3, 5, 7 ], [ 5, 7 ], [ 1, 3 ], null ],
            // 正常系（論理型）
            [ [ false, true ], [ false, true ], [], null ],
            [ [ true, false ], [ false ], [ true ], null ],
            // 正常系（オブジェクト）
            [ [ $object ], [ $object ], [], null ],
            // 正常系（クロージャ）
            [ [ $closure ], [ $closure ], [], null ],
            // 正常系（配列型）
            [ [ [ 'a', 2, 'c' ] ], [ [ 'a', 2, 'c' ] ], [], null ],
            [ [ $object, [ $closure, 1 ] ], [ [ $closure, 1 ] ], [ $object ], null ],
            // 正常系（NULL）
            [ [ null ], [ null ], [], null ],
            [ [ false, 1, 'str', null ], [ null ], [ false, 1, 'str' ], null ],
            // 正常系（イテレーター）
            [ [ 'str', 2, true ], new ListObject([ 'str', 2, true ]), [], null ],
            [ [ $object, 1, $closure, false ], new ListObject([ $closure, false ]), [ $object, 1 ], null ],
        ];
    }

    /**
     * @dataProvider providerAddRange
     */
    public function testAddRange($expected, $value, $base_list)
    {
        $list = new ListObject($base_list);
        $list->addRange($value);

        $method_reflector = (new \ReflectionClass($list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame($expected, $method_reflector->invoke($list)->getArrayCopy());
    }

    public function providerAddRangeFailure()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ 'str', [], '\TypeError' ],
            [ 1, [], '\TypeError' ],
            [ true, [], '\TypeError' ],
            [ $object, [], '\TypeError' ],
            [ $closure, [], '\TypeError' ],
            [ null, [], '\TypeError' ],
        ];
    }

    /**
     * @dataProvider providerAddRangeFailure
     */
    public function testAddRangeFailure($value, $base_list, $expected_exception)
    {
        $this->expectException($expected_exception);
        (new ListObject($base_list))->addRange($value);
    }

    public function providerExists()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ true, [ 1, 3, 4, 5 ], $match ],
            [ false, [ 1, 3, 5, 7 ], $match ]
        ];
    }

    /**
     * @dataProvider providerExists
     */
    public function testExists($expected, $base_list, $match)
    {
        $list = new ListObject($base_list);

        $this->assertSame($expected, $list->exists($match));
    }

    public function providerFind()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ 4, [ 1, 3, 4, 5 ], $match ],
            [ null, [ 1, 3, 5, 7 ], $match ]
        ];
    }

    /**
     * @dataProvider providerFind
     */
    public function testFind($expected, $base_list, $match)
    {
        $list = new ListObject($base_list);

        $this->assertSame($expected, $list->find($match));
    }

    public function providerFindAll()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ [ 2, 4 ], [ 1, 2, 3, 4, 5 ], $match ],
            [ [], [ 1, 3, 5, 7 ], $match ]
        ];
    }

    /**
     * @dataProvider providerFindAll
     */
    public function testFindAll($expected, $base_list, $match)
    {
        $find_list = (new ListObject($base_list))->findAll($match);

        $method_reflector = (new \ReflectionClass($find_list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame($expected, $method_reflector->invoke($find_list)->getArrayCopy());
    }

    public function providerFindIndex()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ 2, [ 1, 3, 4, 5 ], $match, 0, null ],
            [ 2, [ 1, 3, 4, 5 ], $match, 2, null ],
            [ 0, [ 2, 3, 4, 5 ], $match, 0, 1 ],
            [ 3, [ 3, 4, 5, 6 ], $match, 2, 2 ],
            [ 1, [ 3, 4, 5, 6 ], $match, 0, 4 ],
            [ 3, [ 3, 4, 5, 6 ], $match, 3, 1 ],
            [ -1, [ 1, 3, 5, 7 ], $match, 0, null ]
        ];
    }

    /**
     * @dataProvider providerFindIndex
     */
    public function testFindIndex($expected, $base_list, $match, $start_index, $count)
    {
        $list = new ListObject($base_list);

        $this->assertSame($expected, $list->findIndex($match, $start_index, $count));
    }

    public function providerFindIndexFailure()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, -1, null ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 4, null ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 0, -1 ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 0, 5 ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 1, 4 ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 4, 1 ],
        ];
    }

    /**
     * @dataProvider providerFindIndexFailure
     */
    public function testFindIndexFailure($expected, $base_list, $match, $start_index, $count)
    {
        $this->expectException($expected);
        (new ListObject($base_list))->findIndex($match, $start_index, $count);
    }

    public function providerFindLast()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ 6, [ 3, 4, 5, 6 ], $match ],
            [ null, [ 1, 3, 5, 7 ], $match ]
        ];
    }

    /**
     * @dataProvider providerFindLast
     */
    public function testFindLast($expected, $base_list, $match)
    {
        $list = new ListObject($base_list);

        $this->assertSame($expected, $list->findLast($match));
    }

    public function providerFindLastIndex()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ 3, [ 3, 4, 5, 6, 7 ], $match, 0, null ],
            [ 4, [ 2, 3, 4, 5, 6 ], $match, 2, null ],
            [ 2, [ 2, 3, 4, 5, 6 ], $match, 0, 3 ],
            [ 4, [ 4, 5, 6, 7, 8 ], $match, 2, 3 ],
            [ 3, [ 3, 4, 5, 6 ], $match, 0, 4 ],
            [ 3, [ 3, 4, 5, 6 ], $match, 3, 1 ],
            [ -1, [ 1, 3, 5, 7 ], $match, 0, null ]
        ];
    }

    /**
     * @dataProvider providerFindLastIndex
     */
    public function testFindLastIndex($expected, $base_list, $match, $start_index, $count)
    {
        $list = new ListObject($base_list);

        $this->assertSame($expected, $list->findLastIndex($match, $start_index, $count));
    }

    public function providerFindLastIndexFailure()
    {
        $match = function ($v) {
            return ($v % 2 === 0);
        };

        return [
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, -1, null ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 4, null ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 0, -1 ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 0, 5 ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 1, 4 ],
            [ '\OutOfRangeException', [ 1, 3, 4, 5 ], $match, 4, 1 ],
        ];
    }

    /**
     * @dataProvider providerFindLastIndexFailure
     */
    public function testFindLastIndexFailure($expected, $base_list, $match, $start_index, $count)
    {
        $this->expectException($expected);
        (new ListObject($base_list))->findLastIndex($match, $start_index, $count);
    }

    public function providerWalk()
    {
        return [
            [ [ 2, 4, 6, 8 ], [ 1, 2, 3, 4 ] ],
        ];
    }

    /**
     * @dataProvider providerWalk
     */
    public function testWalk($expected, $base_list)
    {
        $hit_list = [];

        $action = function ($value) use (&$hit_list) {
            $hit_list[] = $value * 2;
        };

        (new ListObject($base_list))->walk($action);

        $this->assertCount(count($expected), $hit_list);
        $this->assertSame($expected, $hit_list);
    }

    public function providerGetRange()
    {
        return [
            // 正常系
            [ [ 2, 'str', false, new \stdClass() ], 0, 2, [ 2, 'str' ], null ],
            [ [ 2, 'str', false, new \stdClass() ], 0, 4, [ 2, 'str', false, new \stdClass() ], null ],
            [ [ 2, 'str', false, new \stdClass() ], 1, 2, [ 'str', false ], null ],
            [ [ 2, 'str', false, new \stdClass() ], 3, 1, [ new \stdClass() ], null ],
            // 異常系
            [ [ 2, 'str', false, new \stdClass() ], -1, -1, [], '\OutOfRangeException' ],
            [ [ 2, 'str', false, new \stdClass() ], -1, 2, [], '\OutOfRangeException' ],
            [ [ 2, 'str', false, new \stdClass() ], 0, -1, [], '\OutOfRangeException' ],
            [ [ 2, 'str', false, new \stdClass() ], 0, 0, [], '\InvalidArgumentException' ],
            [ [ 2, 'str', false, new \stdClass() ], 4, 1, [], '\InvalidArgumentException' ],
            [ [ 2, 'str', false, new \stdClass() ], 2, 4, [], '\InvalidArgumentException' ],
        ];
    }

    /**
     * @dataProvider providerGetRange
     */
    public function testGetRange($base_list, $index, $count, $expected, $expected_exception)
    {
        isset($expected_exception) && $this->expectException($expected_exception);

        $this->assertEquals(new ListObject($expected), (new ListObject($base_list))->getRange($index, $count));
    }

    public function providerInsertRange()
    {
        return [
            [ [ 1, 2, 3, 4, 5, 6 ], [ 1, 5, 6 ], 1, [ 2, 3, 4 ] ],
            [ [ 'a', 'b', 'c', 'd' ], [ 'a', 'b' ], 2, new \ArrayObject([ 'c', 'd' ]) ],
            [ [ 1, false, null, 'str' ], [ null, 'str' ], 0, new ListObject([ 1, false ]) ],
        ];
    }

    /**
     * @dataProvider providerInsertRange
     */
    public function testInsertRange($expected, $base_list, $index, $collection)
    {
        $list = new ListObject($base_list);
        $list->insertRange($index, $collection);

        $method_reflector = (new \ReflectionClass($list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($list)->getArrayCopy());
    }

    public function providerInsertRangeFailure()
    {
        return [
            // 異常系（スカラー）
            [ '\TypeError', [], 0, 1 ],
            // 異常系（NULL）
            [ '\TypeError', [], 0, null ],
            // 異常系（オブジェクト）
            [ '\TypeError', [], 0, new \stdClass() ],
            // 異常系（負数インデックス）
            [ '\OutOfRangeException', [], -1, [ 1, 2, 3 ] ],
            // 異常系（サイズ超過）
            [ '\OutOfRangeException', [], 1, new \ArrayObject([ 1, 2, 3 ]) ],
        ];
    }

    /**
     * @dataProvider providerInsertRangeFailure
     */
    public function testInsertRangeFailure($expected, $base_list, $index, $collection)
    {
        $this->expectException($expected);
        (new ListObject($base_list))->insertRange($index, $collection);
    }

    public function providerLastIndexOf()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ 3, [ 1, false, $object, 1, 'str' ], 1, 0, null ],
            [ 3, [ 1, 'str', $object, 'str', $closure ], 'str', 1, null ],
            [ 2, [ $closure, null, $closure, 1 ], $closure, 0, 3 ],
            [ 2, [ 1, false, $object, null, $closure, 'str' ], $object, 1, 3 ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], 0, 0, null ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], false, 3, null ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], $closure, 0, 2 ],
            [ -1, [ 1, false, $object, null, $closure, 'str' ], 'str', 1, 3 ],
        ];
    }

    /**
     * @dataProvider providerLastIndexOf
     */
    public function testLastIndexOf($expected, $base_list, $item, $index, $count)
    {
        $this->assertSame($expected, (new ListObject($base_list))->lastIndexOf($item, $index, $count));
    }

    public function providerLastIndexOfFailure()
    {
        return [
            [ '\OutOfRangeException', [ 1, 2, 3 ], 1, -1, 1 ],
            [ '\OutOfRangeException', [ 1, 2, 3 ], 1, 3, 1 ],
            [ '\OutOfRangeException', [ 1, 2, 3 ], 1, 0, 0 ],
            [ '\OutOfRangeException', [ 1, 2, 3 ], 1, 0, 4 ],
            [ '\OutOfRangeException', [ 1, 2, 3 ], 1, -1, 0 ],
            [ '\OutOfRangeException', [ 1, 2, 3 ], 1, 3, 4 ],
        ];
    }

    /**
     * @dataProvider providerLastIndexOfFailure
     */
    public function testLastIndexOfFailure($expected, $base_list, $item, $index, $count)
    {
        $this->expectException($expected);
        (new ListObject($base_list))->lastIndexOf($item, $index, $count);
    }

    public function providerRemoveAll()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };
        $base_list = [ 1, false, $object, null, $closure, 'str' ];

        return [
            [ [ 1, false, 'str' ], $base_list, function ($v) { return is_scalar($v) === false; } ],
            [ [ $object, null, $closure ], $base_list, function ($v) { return is_scalar($v) === true; } ],
            [ [ $object, $closure ], $base_list, function ($v) { return is_object($v) === false; } ],
            [ [ $closure ], $base_list, function ($v) { return is_callable($v) === false; } ],
            [ [ 1, false, $object, $closure, 'str' ], $base_list, function ($v) { return is_null($v) === true; } ],
            [ [ null ], $base_list, function ($v) { return is_null($v) === false; } ],
        ];
    }

    /**
     * @dataProvider providerRemoveAll
     */
    public function testRemoveAll($expected, $base_list, $match)
    {
        $list = new ListObject($base_list);
        $this->assertSame(count($base_list) - count($expected), $list->removeAll($match));

        $method_reflector = (new \ReflectionClass($list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($list)->getArrayCopy());
    }

    public function providerRemoveRange()
    {
        return [
            [ [ 1, false, null, 'str' ], 0, 1, [ false, null, 'str' ], null ],
            [ [ 1, false, null, 'str' ], 0, 4, [], null ],
            [ [ 1, false, null, 'str' ], 1, 3, [ 1 ], null ],
            [ [ 1, false, null, 'str' ], -1, -1, [], '\OutOfRangeException' ],
            [ [ 1, false, null, 'str' ], 2, -1, [], '\OutOfRangeException' ],
            [ [ 1, false, null, 'str' ], -1, 2, [], '\OutOfRangeException' ],
            [ [ 1, false, null, 'str' ], 0, 0, [], '\InvalidArgumentException' ],
            [ [ 1, false, null, 'str' ], 4, 1, [], '\InvalidArgumentException' ],
            [ [ 1, false, null, 'str' ], 2, 4, [], '\InvalidArgumentException' ],
        ];
    }

    /**
     * @dataProvider providerRemoveRange
     */
    public function testRemoveRange($base_list, $index, $count, $expected, $expected_exception)
    {
        isset($expected_exception) && $this->expectException($expected_exception);

        $list = new ListObject($base_list);
        $list->removeRange($index, $count);

        $method_reflector = (new \ReflectionClass($list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($list)->getArrayCopy());
    }

    public function providerReverse()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };
        $base_list = [ 0, true, $object, null, $closure, 'str' ];

        return [
            [ [ 'str', $closure, null, $object, true, 0 ], $base_list, 0, null ],
            [ [ 0, 'str', $closure, null, $object, true ], $base_list, 1, null ],
            [ [ 0, true, $closure, null, $object, 'str' ], $base_list, 2, 3 ],
            [ [ null, $object, true, 0, $closure, 'str' ], $base_list, 0, 4 ],
        ];
    }

    /**
     * @dataProvider providerReverse
     */
    public function testReverse($expected, $base_list, $index, $count)
    {
        $list = new ListObject($base_list);
        $list->reverse($index, $count);

        $method_reflector = (new \ReflectionClass($list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($list)->getArrayCopy());
    }

    public function providerReverseFailure()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };
        $base_list = [ 0, true, $object, null, $closure, 'str' ];

        return [
            // 引数型異常
            [ '\TypeError', $base_list, $object, $closure ],
            [ '\TypeError', $base_list, 'str', null ],
            // 異常系（インデックス<0・カウント<1）
            [ '\OutOfRangeException', $base_list, -1, 0 ],
            // 異常系（インデックス>最大値・カウント<1）
            [ '\OutOfRangeException', $base_list, 6, 0 ],
            // 異常系（インデックス<0・カウント>最大値）
            [ '\OutOfRangeException', $base_list, -1, 7 ],
            // 異常系（インデックス>最大値・カウント>最大値）
            [ '\OutOfRangeException', $base_list, 6, 7 ],
            // 異常系（インデックス<0・カウント正常値）
            [ '\OutOfRangeException', $base_list, -2, null ],
            // 異常系（インデックス>最大値・カウント正常値）
            [ '\OutOfRangeException', $base_list, 6, 1 ],
            // 異常系（インデックス正常値・カウント<1）
            [ '\OutOfRangeException', $base_list, 0, 0 ],
            // 異常系（インデックス正常値・カウント>最大値）
            [ '\OutOfRangeException', $base_list, 0, 7 ],
        ];
    }

    /**
     * @dataProvider providerReverseFailure
     */
    public function testReverseFailure($expected, $base_list, $index, $count)
    {
        $this->expectException($expected);
        (new ListObject($base_list))->reverse($index, $count);
    }

    public function providerSort()
    {
        $object = new \stdClass();
        $closure = function () {
            return true;
        };

        return [
            [ [ 0, null, true, 'str', $closure, $object ], [ 0, true, $object, null, $closure, 'str' ] ],
            [ [ 1, 2, 3, 4, 5 ], [ 2, 4, 5, 3, 1 ] ],
            [ [ 'a', 'b', 'c', 'd', 'e' ], [ 'e', 'a', 'c', 'b', 'd' ] ],
            [ [ 'android', 'iphone', 'pc', 'smart phone' ], [ 'smart phone', 'iphone', 'pc', 'android' ] ],
        ];
    }

    /**
     * @dataProvider providerSort
     */
    public function testSort($expected, $base_list)
    {
        $list = new ListObject($base_list);
        $list->sort();

        $method_reflector = (new \ReflectionClass($list))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($list)->getArrayCopy());
    }

    public function testToArray()
    {
        $base_list = [ 0, null, true, 'str', new \stdClass() ];
        $this->assertEquals($base_list, (new ListObject($base_list))->toArray());
    }

    public function providerTrueForAll()
    {
        return [
            // 正常系（元が空配列）
            [ true, [], function () { return true; }, null ],
            [ true, [], function () { return false; }, null ],
            // 正常系（全てが条件を満たす）
            [ true, [ 2, 4, 6 ], function ($v) { return ($v % 2 === 0); }, null ],
            [ true, [ 0, false, '', null ], function ($v) { return empty($v); }, null ],
            // 正常系（一部が条件を満たす）
            [ false, [ 1, 2, 3 ], function ($v) { return ($v % 2 === 0); }, null ],
            [ false, [ 0, 1, false, true, 'str' ], function ($v) { return empty($v); }, null ],
            // 正常系（全てが条件を満たさない）
            [ false, [ 1, 3, 5 ], function ($v) { return ($v % 2 === 0); }, null ],
            [ false, [ 1, true, 'str', new \stdClass() ], function ($v) { return empty($v); }, null ],
            // 異常系（クロージャがNULL）
            [ null, [], null, '\TypeError' ],
        ];
    }

    /**
     * @dataProvider providerTrueForAll
     */
    public function testTrueForAll($expected, $base_list, $match, $expected_exception)
    {
        isset($expected_exception) && $this->expectException($expected_exception);

        $this->assertEquals($expected, (new ListObject($base_list))->trueForAll($match));
    }
}
