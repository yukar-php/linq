<?php
namespace Yukar\Linq\Tests\Enumerable;

class TSearchTest extends \PHPUnit_Framework_TestCase
{
    public function providerAll()
    {
        return [
            // 条件式True結果の動作確認
            [ [ 1, 2, 3 ], true, function () { return true; } ],
            // 条件式False結果の動作確認
            [ [ 1, 2, 3 ], false, function () { return false; } ],
            // スカラー型数値の動作確認
            [ [ 1, 2, 3 ], false, function ($v) { return ($v % 2 === 0); } ],
            // 文字列型の動作確認
            [ [ 'a', 'a', 'a' ], true, function ($v) { return ($v === 'a'); } ],
            // 論理型の動作確認
            [ [ true, false, true ], false, function ($v) { return ($v === true); } ],
            // 型混合配列（数値・論理型・オブジェクト型・配列）の動作確認
            [ [ 0, true, new \stdClass(), [ 1 ] ], true, function ($v) { return isset($v); } ],
            // 型混合配列（文字列・小数点数・クロージャ・NULL）の動作確認
            [ [ 'a', 2.1, function() {}, null ], false, function ($v) { return isset($v); } ]
        ];
    }

    /**
     * @dataProvider providerAll
     */
    public function testAll($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('allOf')->will($this->returnValue($expected));

        $result = $mock->allOf(new \ArrayObject($param), $predicate);

        $this->assertSame($expected, $result);
    }

    public function providerAny()
    {
        return [
            // 条件式True結果の動作確認
            [ [ 1, 2, 3 ], true, function () { return true; } ],
            // 条件式False結果の動作確認
            [ [ 1, 2, 3 ], false, function () { return false; } ],
            // スカラー型数値の動作確認
            [ [ 1, 2, 3 ], true, function ($v) { return ($v % 2 === 0); } ],
            // 文字列型の動作確認
            [ [ 'a', 'a', 'a' ], false, function ($v) { return ($v === 'b'); } ],
            // 論理型の動作確認
            [ [ true, false, true ], true, function ($v) { return ($v === true); } ],
            // 型混合配列（数値・論理型・オブジェクト型・配列）の動作確認
            [ [ 0, true, new \stdClass(), [ 1 ] ], false, function ($v) { return isset($v) === false; } ],
            // 型混合配列（文字列・小数点数・クロージャ・NULL）の動作確認
            [ [ 'a', 2.1, function() {}, null ], true, function ($v) { return isset($v); } ]
        ];
    }

    /**
     * @dataProvider providerAny
     */
    public function testAny($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('anyOf')->will($this->returnValue($expected));

        $result = $mock->anyOf(new \ArrayObject($param), $predicate);

        $this->assertSame($expected, $result);
    }

    public function providerContains()
    {
        return [
            // スカラー型数値
            [ [ 1, 2, 3 ], true, 1 ],
            // 文字列型
            [ [ 'a', 'b', 'c' ], false, 'd' ],
            // 論理型
            [ [ true, false, true ], true, true ],
            // オブジェクト型・クロージャ・NULL
            [ [ new \stdClass(), function() {}, null ], false, [ 1, 2, 3 ] ]
        ];
    }

    /**
     * @dataProvider providerContains
     */
    public function testContains($param, $expected, $value)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('containsOf')->will($this->returnValue($expected));

        $result = $mock->containsOf(new \ArrayObject($param), $value);

        $this->assertSame($expected, $result);
    }

    public function providerElementAt()
    {
        return [
            [ [ 1, 2, 3 ], 2, 1 ],
            [ [ 'a', 'b', 'c' ], 'c', 2 ],
            [ [ true, false ], true, 0 ],
            [ [ new \stdClass(), null ], null, 1 ],
            [ [ 1.2, [ 2 ], function () {} ], [ 2 ], 1 ]
        ];
    }

    /**
     * @dataProvider providerElementAt
     */
    public function testElementAt($param, $expected, $index)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('elementAtOf')->will($this->returnValue($expected));

        $result = $mock->elementAtOf(new \ArrayObject($param), $index);

        $this->assertSame($expected, $result);
    }

    public function providerElementAtErrors()
    {
        return [
            // 範囲外（-1以下）
            [ '\OutOfRangeException', [ 1, 2, 3 ], -1 ],
            // 範囲外（長さ以上）
            [ '\OutOfRangeException', [ 'a', 'b', 'c' ], 3 ],
            // NULL
            [ '\InvalidArgumentException', null, 0 ],
            // 数値
            [ '\InvalidArgumentException', 1, 0 ],
            // 文字列型
            [ '\InvalidArgumentException', 'abc', 0 ],
            // 論理型
            [ '\InvalidArgumentException', true, 0 ],
            // 空配列
            [ '\OutOfRangeException', [], 0 ],
            // オブジェクト
            [ '\OutOfRangeException', new \stdClass(), 0 ],
            // クロージャ
            [ '\OutOfRangeException', function () {}, 0 ]
        ];
    }

    /**
     * @dataProvider providerElementAtErrors
     */
    public function testElementAtErrors($expectedException, $param, $index)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('elementAtOf');

        $this->setExpectedException($expectedException);
        $mock->elementAtOf(new \ArrayObject($param), $index);
    }

    public function providerFirst()
    {
        return [
            [ [ 1, 2, 3 ], 2, function ($v) { return ($v % 2 === 0); } ],
            [ [ 2, 3, 4 ], 3, function ($v) { return ($v % 2 !== 0); } ],
            [ [ true, new \stdClass(), [] ], true, function ($v) { return is_bool($v) === true; } ],
            [ [ 'abc', function () {}, null ], null, function ($v) { return isset($v) === false; } ]
        ];
    }

    /**
     * @dataProvider providerFirst
     */
    public function testFirst($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('firstOf')->will($this->returnValue($expected));

        $result = $mock->firstOf(new \ArrayObject($param), $predicate);

        $this->assertSame($expected, $result);
    }

    public function providerLast()
    {
        return [
            [ [ 1, 2, 3 ], 3, function ($v) { return ($v % 2 !== 0); } ],
            [ [ 2, 3, 4 ], 4, function ($v) { return ($v % 2 === 0); } ],
            [ [ new \stdClass(), true, [] ], true, function ($v) { return is_array($v) === false; } ],
            [ [ function () {}, 'abc', null ], 'abc', function ($v) { return isset($v) === true; } ]
        ];
    }

    /**
     * @dataProvider providerLast
     */
    public function testLast($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('lastOf')->will($this->returnValue($expected));

        $result = $mock->lastOf(new \ArrayObject($param), $predicate);

        $this->assertSame($expected, $result);
    }

    public function providerSingle()
    {
        return [
            [ [ 1, 2, 3 ], 2, function ($v) { return ($v % 2 === 0); } ],
            [ [ 2, 3, 4 ], 3, function ($v) { return ($v % 2 !== 0); } ],
            [ [ true, new \stdClass(), [] ], true, function ($v) { return is_bool($v) === true; } ],
            [ [ 'abc', function () {}, null ], null, function ($v) { return isset($v) === false; } ]
        ];
    }

    /**
     * @dataProvider providerSingle
     */
    public function testSingle($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('singleOf')->will($this->returnValue($expected));

        $result = $mock->singleOf(new \ArrayObject($param), $predicate);

        $this->assertSame($expected, $result);
    }

    public function providerSingleErrors()
    {
        return [
            [ '\LogicException', [ 1, 2, 3 ], function ($v) { return ($v % 2 !== 0); } ],
            [ '\LogicException', [ 2, 3, 4 ], function ($v) { return ($v % 2 === 0); } ],
            [ '\LogicException', [ true, new \stdClass(), [] ], function ($v) { return is_bool($v) === false; } ],
            [ '\LogicException', [ 'abc', function () {}, null ], function ($v) { return isset($v) === true; } ]
        ];
    }

    /**
     * @dataProvider providerSingleErrors
     */
    public function testSingleErrors($expectedException, $param, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TSearch');
        $mock->expects($this->any())->method('singleOf');

        $this->setExpectedException($expectedException);
        $mock->singleOf(new \ArrayObject($param), $predicate);
    }
}
