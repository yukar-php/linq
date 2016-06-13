<?php
namespace Yukar\Linq\Tests\Enumerable;

use Yukar\Linq\Collections\KeyValuePair;

class TSetTest extends \PHPUnit_Framework_TestCase
{
    public function providerExcept()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], [ 1, 3, 5 ], [ 2 ] ],
            // 文字列型
            [ [ 'a', 'b', 'c' ], [ 'x', 'y', 'a' ], [ 'b', 'c' ] ],
            // 論理型
            [ [ true, false ], [ false, true ], [] ],
            // オブジェクト型
            [ [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ], [] ],
            // 型混合
            [ [ 0, false, '0', 0.0, null ], [ 0 ], [ false, null ] ],
            // 型混合
            [ [ 1, true, '1', 1, 'a' ], [ 1 ], [ 'a' ] ]
        ];
    }

    /**
     * @dataProvider providerExcept
     */
    public function testExcept($param, $second, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSet');

        $result = $mock->exceptOf(new \ArrayObject($param), new \ArrayObject($second));

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerIntersect()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], [ 1, 3, 5 ], [ 1, 3 ] ],
            // 文字列型
            [ [ 'a', 'b', 'c' ], [ 'a', 'c', 'x' ], [ 'a', 'c' ] ],
            // 論理型
            [ [ true, false ], [ false, true ], [ true, false ] ],
            // オブジェクト型
            [ [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ] ],
            // 型混合
            [ [ 0, false, '0', 0.0, null ], [ 0 ], [ 0, '0', 0.0 ] ],
            // 型混合
            [ [ 1, true, '1', 1.0, 'a' ], [ 1 ], [ 1, true, '1', 1.0 ] ]
        ];
    }

    /**
     * @dataProvider providerIntersect
     */
    public function testIntersect($param, $second, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSet');

        $result = $mock->intersectOf(new \ArrayObject($param), new \ArrayObject($second));

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerUnion()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], [ 1, 3, 5 ], [ 1, 2, 3, 5 ] ],
            // 文字列型
            [ [ 'a', 'b', 'c' ], [ 'a', 'c', 'x' ], [ 'a', 'b', 'c', 'x' ] ],
            // 論理型
            [ [ true, false ], [ false, true ], [ true, false ] ],
            // オブジェクト型
            [ [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ] ],
            // 型混合
            [ [ 0, false, '0', 0.0, null ], [ 0 ], [ 0, null ] ],
            // 型混合
            [ [ 1, true, '1', 1.0, 'a' ], [ 1 ], [ 1, 'a' ] ]
        ];
    }

    /**
     * @dataProvider providerUnion
     */
    public function testUnion($param, $second, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSet');

        $result = $mock->unionOf(new \ArrayObject($param), new \ArrayObject($second));

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerConcat()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], [ 1, 3, 5 ], [ 1, 2, 3, 1, 3, 5 ] ],
            // 文字列型
            [ [ 'a', 'b', 'c' ], [ 'a', 'c', 'x' ], [ 'a', 'b', 'c', 'a', 'c', 'x' ] ],
            // 論理型
            [ [ true, false ], [ false, true ], [ true, false, false, true ] ],
            // オブジェクト型
            [ [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value'), new KeyValuePair('key', 'value') ] ],
            // 型混合
            [ [ 0, false, '0', 0.0, null ], [ 0 ], [ 0, false, '0', 0.0, null, 0 ] ],
            // 型混合
            [ [ 1, true, '1', 1.0, 'a' ], [ 1 ], [ 1, true, '1', 1.0, 'a', 1 ] ]
        ];
    }

    /**
     * @dataProvider providerConcat
     */
    public function testConcat($param, $second, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSet');

        $result = $mock->concatOf(new \ArrayObject($param), new \ArrayObject($second));

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerZip()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], [ 1, 3, 5 ], [ 1, 6, 15 ], function ($v1, $v2) { return $v1 * $v2; } ],
            // 文字列型
            [ [ 'a', 'c', 'e' ], [ 'b', 'd', 'f' ], [ 'ab', 'cd', 'ef' ], function ($v1, $v2) { return $v1 . $v2; } ],
            // 論理型
            [ [ true, false ], [ false, true ], [ false, false ], function ($v1, $v2) { return $v1 === $v2; } ],
            // 型混合
            [ [ 0, false, '0', 0.0, null ], [ 0 ], [ 0 ], function ($v1, $v2) { return $v1 - $v2; } ],
            // 型混合
            [ [ 1, true, '1', 1.0, 'a' ], [ 1 ], [ 2 ], function ($v1, $v2) { return $v1 + $v2; } ]
        ];
    }

    /**
     * @dataProvider providerZip
     */
    public function testZip($param, $second, $expectedParam, $resultSelector)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSet');

        $result = $mock->zipOf(new \ArrayObject($param), new \ArrayObject($second), $resultSelector);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerSequenceEqual()
    {
        return [
            // 同じ配列
            [ [ 1, 2, 3 ], [ 1, 2, 3 ], true ],
            // 違う配列
            [ [ 'a', 'b', 'c' ], [ 'x', 'y', 'z' ], false ],
            // 値は同じだが、順番が違う
            [ [ 0.1, 1.5, 2.1 ], [ 2.1, 1.5, 0,1 ], false ],
            // 長さが違うが、含まれる内容は同じ
            [ [ 1, 'a' ], [ 1, 'a', 3 ], false ],
            // 長さが違うが、含まれる内容は同じ
            [ [ true, 1, 'a' ], [ true, 1 ], false ],
            // オブジェクト型
            [ [ new KeyValuePair('key', 'value') ], [ new KeyValuePair('key', 'value') ], true ],
            // 型混合配列
            [ [ true, false, 'x' ], [ true, false, 'x' ], true ]
        ];
    }

    /**
     * @dataProvider providerSequenceEqual
     */
    public function testSequenceEqual($param, $second, $expected)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSet');

        $this->assertEquals($expected, $mock->sequenceEqualOf(new \ArrayObject($param), new \ArrayObject($second)));
    }
}
