<?php
namespace Yukar\Linq\Tests\Enumerable;

class TExtractTest extends \PHPUnit_Framework_TestCase
{
    public function providerSkip()
    {
        return [
            // 数値
            [ [ 1, 2, 3, 4, 5 ], new \ArrayObject([ 3, 4, 5 ]), 2 ],
            // 文字列
            [ [ 'a', 'b', 'c', 'd', 'e' ], new \ArrayObject([ 'd', 'e' ]), 3 ],
            // 型混合
            [ [ true, new \stdClass(), null, [ 1, 2 ] ], new \ArrayObject([ new \stdClass(), null, [ 1, 2 ] ]), 1 ],
            // 負数
            [ [ 1, 2, 3 ], new \ArrayObject([ 1, 2, 3 ]), -1 ],
            // 上限超過
            [ [ 'a', 'b', 'c' ], new \ArrayObject([]), 4 ]
        ];
    }

    /**
     * @dataProvider providerSkip
     */
    public function testSkip($param, $expected, $count)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('skip')->will($this->returnValue($expected));

        $result = $mock->skip(new \ArrayObject($param), $count);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerSkipErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\TypeError', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\TypeError', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\TypeError', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 片方正常値・片方異常値（ArrayObjectと配列）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), [ 1, 2, 3 ] ],
            // 片方正常値・片方異常値（クロージャと数値型）
            [ '\TypeError', function () { return true; }, 1 ]
        ];
    }

    /**
     * @dataProvider providerSkipErrors
     */
    public function testSkipErrors($expectedException, $source, $count)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('skip');

        $this->setExpectedException($expectedException);
        $mock->skip($source, $count);
    }

    public function providerSkipWhile()
    {
        return [
            // 数値
            [ [ 1, 3, 5, 6, 9 ], new \ArrayObject([ 6, 9 ]), function ($v) { return ($v % 2 !== 0); } ],
            // 文字列
            [ [ '0', 'a', '!' ], new \ArrayObject([ 'a', '!' ]), function ($v) { return (preg_match('/[a-z]/', $v) === 0); } ],
            // 型混合
            [ [ true, new \stdClass(), null, [ 1, 2 ] ], new \ArrayObject([ null, [ 1, 2 ] ]), function ($v) { return isset($v); } ],
            // 条件が全てFALSE
            [ [ 1, 2, 3 ], new \ArrayObject([ 1, 2, 3 ]), function ($v) { return is_string($v); } ],
            // 条件が全てTRUE
            [ [ 'a', 'b', 'c' ], new \ArrayObject([]), function ($v) { return is_string($v); } ]
        ];
    }

    /**
     * @dataProvider providerSkipWhile
     */
    public function testSkipWhile($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('skipWhile')->will($this->returnValue($expected));

        $result = $mock->skipWhile(new \ArrayObject($param), $predicate);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerSkipWhileErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\TypeError', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\TypeError', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\TypeError', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 片方正常値・片方異常値（ArrayObjectと配列）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), [ 1, 2, 3 ] ],
            // 片方正常値・片方異常値（NULLとクロージャ）
            [ '\TypeError', null, function () { return true; } ]
        ];
    }

    /**
     * @dataProvider providerSkipWhileErrors
     */
    public function testSkipWhileErrors($expectedException, $source, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('skipWhile');

        $this->setExpectedException($expectedException);
        $mock->skipWhile($source, $predicate);
    }

    public function providerTake()
    {
        return [
            // 数値
            [ [ 1, 2, 3, 4, 5 ], new \ArrayObject([ 1, 2 ]), 2 ],
            // 文字列
            [ [ 'a', 'b', 'c', 'd', 'e' ], new \ArrayObject([ 'a', 'b', 'c' ]), 3 ],
            // 型混合
            [ [ true, new \stdClass(), null, [ 1, 2 ] ], new \ArrayObject([ true ]), 1 ],
            // 負数
            [ [ 1, 2, 3 ], new \ArrayObject([]), -1 ],
            // 上限超過
            [ [ 'a', 'b', 'c' ], new \ArrayObject([ 'a', 'b', 'c' ]), 4 ]
        ];
    }

    /**
     * @dataProvider providerTake
     */
    public function testTake($param, $expected, $count)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('take')->will($this->returnValue($expected));

        $result = $mock->take(new \ArrayObject($param), $count);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerTakeErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\TypeError', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\TypeError', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\TypeError', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 片方正常値・片方異常値（ArrayObjectと配列）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), [ 1, 2, 3 ] ],
            // 片方正常値・片方異常値（クロージャと数値型）
            [ '\TypeError', function () { return true; }, 1 ]
        ];
    }

    /**
     * @dataProvider providerTakeErrors
     */
    public function testTakeErrors($expectedException, $source, $count)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('take');

        $this->setExpectedException($expectedException);
        $mock->take($source, $count);
    }

    public function providerTakeWhile()
    {
        return [
            // 数値
            [ [ 1, 3, 5, 6, 9 ], new \ArrayObject([ 1, 3, 5 ]), function ($v) { return ($v % 2 !== 0); } ],
            // 文字列
            [ [ '0', '%', 'a', '!' ], new \ArrayObject([ '0', '%' ]), function ($v) { return (preg_match('/[a-z]/', $v) === 0); } ],
            // 型混合
            [ [ true, new \stdClass(), null, [ 1, 2 ] ], new \ArrayObject([ true, new \stdClass() ]), function ($v) { return isset($v); } ],
            // 条件が全てFALSE
            [ [ 1, 2, 3 ], new \ArrayObject([]), function ($v) { return is_string($v); } ],
            // 条件が全てTRUE
            [ [ 'a', 'b', 'c' ], new \ArrayObject([ 'a', 'b', 'c' ]), function ($v) { return is_string($v); } ]
        ];
    }

    /**
     * @dataProvider providerTakeWhile
     */
    public function testTakeWhile($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('skipWhile')->will($this->returnValue($expected));

        $result = $mock->takeWhile(new \ArrayObject($param), $predicate);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerTakeWhileErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\TypeError', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\TypeError', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\TypeError', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 片方正常値・片方異常値（ArrayObjectと配列）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), [ 1, 2, 3 ] ],
            // 片方正常値・片方異常値（NULLとクロージャ）
            [ '\TypeError', null, function () { return true; } ]
        ];
    }

    /**
     * @dataProvider providerTakeWhileErrors
     */
    public function testTakeWhileErrors($expectedException, $source, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TExtract');
        $mock->expects($this->any())->method('takeWhile');

        $this->setExpectedException($expectedException);
        $mock->takeWhile($source, $predicate);
    }
}
