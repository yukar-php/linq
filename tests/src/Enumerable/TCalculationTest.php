<?php
namespace Yukar\Linq\Tests\Enumerable;

class TCalculationTest extends \PHPUnit_Framework_TestCase
{
    public function providerSum()
    {
        return [
            // 整数値
            [ [ 1, 2, 3 ], 6 ],
            // 小数点数
            [ [ 1.1, 2.5, 3.4 ], 7.0 ],
            // 論理型
            [ [ false, true, false ], 1 ],
            // 数値文字列
            [ [ '1', '2.5', '3' ], 6.5 ],
            // 型複合
            [ [ 1, 2.5, true, '2.8' ], 7.3 ],
            // 条件付き整数値
            [ [ 1, 2, 3, 4, 5 ], 3, function ($v) { return $v % 2; } ],
            // 条件付き型複合
            [ [ 1, 2.5, true, '2.8' ], 14.6, function ($v) { return $v * 2; } ]
        ];
    }

    /**
     * @dataProvider providerSum
     */
    public function testSum($param, $expected, $selector = null)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('sumOf')->will($this->returnValue($expected));

        $this->assertEquals($expected, $mock->sumOf(new \ArrayObject($param), $selector));
    }

    public function providerSumErrors()
    {
        return [
            // NULL
            [ '\UnexpectedValueException', [ null, null ] ],
            // 配列型
            [ '\UnexpectedValueException', [ [ 1 ], [ 2 ] ] ],
            // オブジェクト型
            [ '\UnexpectedValueException', [ new \stdClass(), new \stdClass() ] ],
            // クロージャ
            [ '\UnexpectedValueException', [ function () {}, function () {} ] ]
        ];
    }

    /**
     * @dataProvider providerSumErrors
     */
    public function testSumErrors($expectedException, $param)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('sumOf');

        $this->setExpectedException($expectedException);
        $mock->sumOf(new \ArrayObject($param));
    }

    public function providerAverage()
    {
        return [
            // 整数値
            [ [ 1, 2, 3 ], 2 ],
            // 小数点数
            [ [ 1.5, 2.8, 4.7 ], 3.0 ],
            // 論理型
            [ [ false, true, false, true ], 0.5 ],
            // 数値文字列
            [ [ '1.5', '3.0', '4.5' ], 3.0 ],
            // 型複合
            [ [ 1, 1.5, true, '4.5' ], 2.0 ],
            // 条件付き整数値
            [ [ 1, 2, 3, 4, 5 ], 0.6, function ($v) { return $v % 2; } ],
            // 条件付き型複合
            [ [ 1, 2.5, true, '2.8' ], 3.65, function ($v) { return $v * 2; } ]
        ];
    }

    /**
     * @dataProvider providerAverage
     */
    public function testAverage($param, $expected, $selector = null)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('averageOf')->will($this->returnValue($expected));

        $this->assertEquals($expected, $mock->averageOf(new \ArrayObject($param), $selector));
    }

    public function providerAverageErrors()
    {
        return [
            // NULL
            [ '\UnexpectedValueException', [ null, null ] ],
            // 配列型
            [ '\UnexpectedValueException', [ [ 1 ], [ 2 ] ] ],
            // オブジェクト型
            [ '\UnexpectedValueException', [ new \stdClass(), new \stdClass() ] ],
            // クロージャ
            [ '\UnexpectedValueException', [ function () {}, function () {} ] ]
        ];
    }

    /**
     * @dataProvider providerAverageErrors
     */
    public function testAverageErrors($expectedException, $param)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('averageOf');

        $this->setExpectedException($expectedException);
        $mock->averageOf(new \ArrayObject($param));
    }

    public function providerMax()
    {
        return [
            // 整数値
            [ [ 1, 2, 3 ], 3 ],
            // 小数点数
            [ [ 1.1, 2.5, 3.4 ], 3.4 ],
            // 論理型
            [ [ false, true, false ], true ],
            // 数値文字列
            [ [ '1', '2.5', '3' ], 3 ],
            // 型複合
            [ [ 1, 2.5, true, '2.8' ], 2.8 ],
            // 条件付き整数値
            [ [ 1, 2, 3, 4, 5 ], 1, function ($v) { return $v % 2; } ],
            // 条件付き型複合
            [ [ 1, 2.5, true, '2.8' ], 5.6, function ($v) { return $v * 2; } ]
        ];
    }

    /**
     * @dataProvider providerMax
     */
    public function testMax($param, $expected, $selector = null)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('maxOf')->will($this->returnValue($expected));

        $this->assertEquals($expected, $mock->maxOf(new \ArrayObject($param), $selector));
    }

    public function providerMaxErrors()
    {
        return [
            // NULL
            [ '\UnexpectedValueException', [ null, null ] ],
            // 配列型
            [ '\UnexpectedValueException', [ [ 1 ], [ 2 ] ] ],
            // オブジェクト型
            [ '\UnexpectedValueException', [ new \stdClass(), new \stdClass() ] ],
            // クロージャ
            [ '\UnexpectedValueException', [ function () {}, function () {} ] ]
        ];
    }

    /**
     * @dataProvider providerMaxErrors
     */
    public function testMaxErrors($expectedException, $param)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('maxOf');

        $this->setExpectedException($expectedException);
        $mock->maxOf(new \ArrayObject($param));
    }

    public function providerMin()
    {
        return [
            // 整数値
            [ [ 1, 2, 3 ], 1 ],
            // 小数点数
            [ [ 1.1, 2.5, 3.4 ], 1.1 ],
            // 論理型
            [ [ false, true, false ], false ],
            // 数値文字列
            [ [ '1', '2.5', '3' ], 1 ],
            // 型複合
            [ [ 1, 2.5, true, '2.8' ], 1 ],
            // 条件付き整数値
            [ [ 1, 2, 3, 4, 5 ], 0, function ($v) { return $v % 2; } ],
            // 条件付き型複合
            [ [ 1, 2.5, true, '2.8' ], 2, function ($v) { return $v * 2; } ]
        ];
    }

    /**
     * @dataProvider providerMin
     */
    public function testMin($param, $expected, $selector = null)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('minOf')->will($this->returnValue($expected));

        $this->assertEquals($expected, $mock->minOf(new \ArrayObject($param), $selector));
    }

    public function providerMinErrors()
    {
        return [
            // NULL
            [ '\UnexpectedValueException', [ null, null ] ],
            // 配列型
            [ '\UnexpectedValueException', [ [ 1 ], [ 2 ] ] ],
            // オブジェクト型
            [ '\UnexpectedValueException', [ new \stdClass(), new \stdClass() ] ],
            // クロージャ
            [ '\UnexpectedValueException', [ function () {}, function () {} ] ]
        ];
    }

    /**
     * @dataProvider providerMinErrors
     */
    public function testMinErrors($expectedException, $param)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('minOf');

        $this->setExpectedException($expectedException);
        $mock->minOf(new \ArrayObject($param));
    }

    public function providerCount()
    {
        return [
            // 数値型
            [ [ 1, 2, 3, 1.1, 2.5 ], 5 ],
            // 論理型
            [ [ false, true, false ], 3 ],
            // 文字列型
            [ [ '1', '2.5', '3', 'abc', 'xyz', '!$%' ], 6 ],
            // 型複合
            [ [ 1, 2.5, true, '2.8', null ], 5 ],
            // 条件付き整数値
            [ [ 1, 2, 3, 4, 5 ], 2, function ($v) { return ($v % 2 === 0); } ],
            // 条件付き型複合
            [ [ 1, 2.5, true, '2.8' ], 2, function ($v) { return ($v > 1); } ]
        ];
    }

    /**
     * @dataProvider providerCount
     */
    public function testCount($param, $expected, $predicate = null)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('countOf')->will($this->returnValue($expected));

        $this->assertEquals($expected, $mock->countOf(new \ArrayObject($param), $predicate));
    }

    public function providerAggregate()
    {
        return [
            // 加法
            [ [ 1, 2, 3 ], 6, function ($r, $v) { return $r + $v; } ],
            // 減法
            [ [ 10, 5, 1 ], 4, function ($r, $v) { return $r - $v; } ],
            // 乗法
            [ [ 2, 3, 4 ], 24, function ($r, $v) { return $r * $v; } ],
            // 除法
            [ [ 60, 10, 2 ], 3, function ($r, $v) { return $r / $v; } ],
            // 累乗
            [ [ 2, 3, 4 ], 4096, function ($r, $v) { return pow($r, $v); } ]
        ];
    }

    /**
     * @dataProvider providerAggregate
     */
    public function testAggregate($param, $expected, $func)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('aggregateOf')->will($this->returnValue($expected));

        $this->assertEquals($expected, $mock->aggregateOf(new \ArrayObject($param), $func));
    }

    public function providerAggregateErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\InvalidArgumentException', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\InvalidArgumentException', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\InvalidArgumentException', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 片方正常値・片方異常値（ArrayObjectとスカラー型数値）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), 1 ],
            // 片方正常値・片方異常値（スカラー型数値とクロージャ）
            [ '\InvalidArgumentException', 0.0, function () { return true; } ],
        ];
    }

    /**
     * @dataProvider providerAggregateErrors
     */
    public function testAggregateErrors($expectedException, $param, $func)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCalculation');
        $mock->expects($this->any())->method('aggregateOf');

        $this->setExpectedException($expectedException);
        $mock->aggregateOf(new \ArrayObject($param), $func);
    }
}
