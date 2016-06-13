<?php
namespace Yukar\Linq\Tests\Utilities;

use Yukar\Linq\Utilities\ArrayControl;

class ArrayControlTest extends \PHPUnit_Framework_TestCase
{
    public function providerGetValue()
    {
        return [
            // 数値型
            [ [1, 2, 3], 1, null, 2 ],
            // 文字列型
            [ ['a' => 1, 'b' => 2, 'c' => 3], 'b', null, 2 ],
            // 範囲外（数値）
            [ [1, 2, 3], 3, null, null ],
            // 範囲外（文字列）
            [ ['a' => 1, 'b' => 2, 'c' => 3], 'd', null, null ],
            // null
            [ [1, 2, 3], null, 1, 1 ],
            // 論理型
            [ [1, 2, 3], true, null, null ],
            [ [1, 2, 3], false, null, null ],
            // 小数点数
            [ [1, 2, 3], 1.2, null, null ],
            // 負数
            [ [1, 2, 3], -1, null, null ],
            // オブジェクト型
            [ [1, 2, 3], new \stdClass(), null, null ],
            // クロージャ
            [ [1, 2, 3], function () { return false; }, null, null ],
        ];
    }

    /**
     * @dataProvider providerGetValue
     */
    public function testGetValue($param, $key, $default, $expected)
    {
        $this->assertSame($expected, ArrayControl::getValue($param, $key, $default));
    }

    public function providerFindValue()
    {
        return [
            [ [ 1, [ 4, 5, 6 ], 3 ], '1=>2', 6 ],
            [ [ 'a' => 0, 'b' => 1, 'c' => [ 'x', 'y', 'z' ] ], 'c=>1', 'y' ],
            [ [ 1, [ 4, 5, 6 ], 3 ], '3=>2', 'NaN', 'NaN' ],
            [ [ 'a' => 0, 'b' => 1, 'c' => [ 'x', 'y', 'z' ] ], 'd', 'NaN', 'NaN' ],
            [ [ 1, [ 4, 5, 6 ], 3 ], 'true=>false=>null', 'NaN', 'NaN' ],
            [ [ 1, [ 4, 5, 6 ], 3 ], '1.2=>-1', 'NaN', 'NaN' ],
        ];
    }

    /**
     * @dataProvider providerFindValue
     */
    public function testFindValue($param, $key, $expected, $default = null)
    {
        $this->assertSame($expected, ArrayControl::findValue($param, $key, $default));
    }

    public function providerPutValue()
    {
        return [
            // 数値型
            [ [], '1=>2=>key', 1, [ 1 => [ 2 => [ 'key' => 1 ] ] ] ],
            [ [], '1=>2=>key', 1.5, [ 1 => [ 2 => [ 'key' => 1.5 ] ] ] ],
            // 文字列型
            [ [], '1=>2=>key', 'a', [ 1 => [ 2 => [ 'key' => 'a' ] ] ] ],
            [ [], '1=>2=>key', '3', [ 1 => [ 2 => [ 'key' => '3' ] ] ] ],
            // 論理型
            [ [], '1=>2=>key', true, [ 1 => [ 2 => [ 'key' => true ] ] ] ],
            [ [], '1=>2=>key', false, [ 1 => [ 2 => [ 'key' => false ] ] ] ],
            // null
            [ [], '1=>2=>key', null, [ 1 => [ 2 => [ 'key' => null ] ] ] ],
            // オブジェクト型
            [ [], '1=>2=>key', new \stdClass(), [ 1 => [ 2 => [ 'key' => new \stdClass() ] ] ] ],
            // クロージャ
            [ [], '1=>2=>key', function() { return false; }, [ 1 => [ 2 => [ 'key' => function() { return false; } ] ] ] ],
        ];
    }

    /**
     * @dataProvider providerPutValue
     */
    public function testPutValue($param, $key, $item, $expected)
    {
        ArrayControl::putValue($param, $key, $item);
        $this->assertEquals($expected, $param);
    }

    public function testEachWalk()
    {
        $list = range(1, 10);
        $result = ArrayControl::eachWalk(
            $list,
            function ($v, $k, $result) {
                return $result + $v;
            }
        );

        $this->assertSame(55, $result);
    }

    public function providerCopyWhen()
    {
        return [
            [ true, [], [ 1, 2, 3 ], [ 1, 2, 3 ] ],
            [ true, [ 'a', 'b' ], [ 1, 2 ], [ 1, 2 ] ],
            [ false, [], [ 'x', 'y', 'z' ], [] ],
            [ true, [ 'a', 'b' ], function () { return [ 1 ]; }, [ 1 ] ],
            [ false, [ 1, 2 ], function () { return [ 'x' ]; }, [ 1, 2 ] ],
        ];
    }

    /**
     * @dataProvider providerCopyWhen
     */
    public function testCopyWhen($conditions, $to, $from, $expected)
    {
        ArrayControl::copyWhen($conditions, $to, $from);

        $this->assertCount(count($expected), $to);
        $this->assertSame($expected, $to);
    }

    public function providerMergeWhen()
    {
        return [
            [ true, [], [ 1, 2, 3 ], [ 1, 2, 3 ] ],
            [ true, [ 'a', 'b' ], [ 2 => 1, 3 => 2 ], [ 'a', 'b', 1, 2 ] ],
            [ true, [ [ 0 => 'a' ], 'b' ], [ [ 1 => 'x' ], 'y' ], [ [ 'a', 'x'], 'y' ] ],
            [ false, [], [ 1, 'x', 2, 'y', 3, 'z' ], [] ],
            [ true, [ 'a', 'b' ], function () { return [ 2 => 1 ]; }, [ 'a', 'b', 1 ] ],
            [ false, [ 1, 2 ], function () { return [ 'x' ]; }, [ 1, 2 ] ],
            [ true, [], [ null ], [] ],
            [ true, [], [ -1 => 1, -2 => 2, -3 => 3 ], [ 1, 2, 3 ] ],
        ];
    }

    /**
     * @dataProvider providerMergeWhen
     */
    public function testMergeWhen($conditions, $target, $merged, $expected)
    {
        ArrayControl::mergeWhen($conditions, $target, $merged);

        $this->assertCount(count($expected), $target);
        $this->assertSame($expected, $target);
    }
}
