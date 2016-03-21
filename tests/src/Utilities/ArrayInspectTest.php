<?php
namespace Yukar\Linq\Tests\Utilities;

use Yukar\Linq\Utilities\ArrayInspect;

class ArrayInspectTest extends \PHPUnit_Framework_TestCase
{
    public function providerIsEmpty()
    {
        return [
            [ [], true ],
            [ [ 1 ], false ],
            [ [ '2', 2.1, true, new \stdClass() ], false ],
        ];
    }

    /**
     * @dataProvider providerIsEmpty
     */
    public function testIsEmpty($param, $expected)
    {
        $this->assertSame($expected, ArrayInspect::isEmpty($param));
    }

    public function providerIsValid()
    {
        return [
            [ [], false ],
            [ [ 'a' ], true ],
            [ [ 1, 2.1, 'b', false, new \stdClass() ], true ]
        ];
    }

    /**
     * @dataProvider providerIsValid
     */
    public function testIsValid($param, $expected)
    {
        $this->assertSame($expected, ArrayInspect::isValid($param));
    }

    public function providerIsValidKey()
    {
        return [
            // 数値型
            [ 0, true ],
            [ 1, true ],
            [ 1.2, false ],
            // 文字列型
            [ '1', true ],
            [ 'a', true ],
            // 負数
            [ -1, false ],
            // 空文字列
            [ '', false ],
            // 論理型
            [ true, false ],
            [ false, false ],
            // オブジェクト型
            [ new \stdClass(), false ],
            // null
            [ null, false ],
            // クロージャ
            [ function () { return true; }, false ]
        ];
    }

    /**
     * @dataProvider providerIsValidKey
     */
    public function testIsValidKey($param, $expected)
    {
        $this->assertSame($expected, ArrayInspect::isValidKey($param));
    }

    public function providerIsContainsKey()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], 1, true ],
            // 文字列型
            [ [ 'a' => 1, 'b' => 2, 'c' => 3 ], 'a', true ],
            // 範囲外（数値）
            [ [ 1, 2, 3 ], 3, false ],
            // 範囲外（文字列）
            [ [ 'a' => 1, 'b' => 2, 'c' => 3 ], 'd', false ],
            // null
            [ [ 1, 2, 3 ], null, false ],
            // 論理型
            [ [ 1, 2, 3 ], true, false ],
            [ [ 1, 2, 3 ], false, false ],
            // 小数点数
            [ [ 1, 2, 3 ], 1.2, false ],
            // 負数
            [ [ 1, 2, 3 ], -1, false ],
            // オブジェクト型
            [ [ 1, 2, 3 ], new \stdClass(), false ],
            // クロージャ
            [ [ 1, 2, 3 ], function () { return false; }, false ],
        ];
    }

    /**
     * @dataProvider providerIsContainsKey
     */
    public function testIsContainsKey($param, $key, $expected)
    {
        $this->assertSame($expected, ArrayInspect::isContainsKey($param, $key));
    }

    public function providerIsExistValue()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], 1, true ],
            // 文字列型
            [ [ 'a' => 1, 'b' => 2, 'c' => 3 ], 'a', true ],
            // 範囲外（数値）
            [ [ 1, 2, 3 ], 3, false ],
            // 範囲外（文字列）
            [ [ 'a' => 1, 'b' => 2, 'c' => 3 ], 'd', false ],
            // 値がNULL
            [ [ 1, 2, null ], 2, false ],
            // null
            [ [ 1, 2, 3 ], null, false ],
            // 論理型
            [ [ 1, 2, 3 ], true, false ],
            [ [ 1, 2, 3 ], false, false ],
            // 小数点数
            [ [ 1, 2, 3 ], 1.2, false ],
            // 負数
            [ [ 1, 2, 3 ], -1, false ],
            // オブジェクト型
            [ [ 1, 2, 3 ], new \stdClass(), false ],
            // クロージャ
            [ [ 1, 2, 3 ], function () { return false; }, false ],
        ];
    }

    /**
     * @dataProvider providerIsExistValue
     */
    public function testIsExistValue($param, $key, $expected)
    {
        $this->assertSame($expected, ArrayInspect::isExistValue($param, $key));
    }
}
