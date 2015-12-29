<?php
namespace Yukar\Linq\Tests\Enumerable;

class TCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyList()
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('emptyList')->will($this->returnValue(new \ArrayObject([])));

        $result = $mock->emptyList();

        $this->assertInstanceOf('\ArrayObject', $result);
    }

    public function providerAsEnumerable()
    {
        return [
            // 数値型
            [ [ 1, 2, 3 ], [ 1, 2, 3 ] ],
            // 文字列型
            [ [ 'a', 'b', 'c' ], [ 'a', 'b', 'c' ] ],
            // 論理型
            [ [ true, false], [ true, false ] ],
            // 配列
            [ [ [ 1 ], [ 1, 2 ] ], [ [ 1 ], [ 1, 2 ] ] ],
            // オブジェクト型
            [ [ new \stdClass(), new \stdClass() ], [ new \stdClass(), new \stdClass() ] ]
        ];
    }

    /**
     * @dataProvider providerAsEnumerable
     */
    public function testAsEnumerable($param, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('asEnumerable')->will($this->returnValue($expected));

        $result = $mock->asEnumerable(new \ArrayObject($param));

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerGenerateRange()
    {
        return [
            [ 1, 5, new \ArrayObject([ 1, 2, 3, 4, 5 ]) ],
            [ -4, 9, new \ArrayObject([ -4, -3, -2, -1, 0, 1, 2, 3, 4]) ]
        ];
    }

    /**
     * @dataProvider providerGenerateRange
     */
    public function testGenerateRange($start, $count, $expected)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('generateRange')->will($this->returnValue($expected));

        $result = $mock->generateRange($start, $count);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerGenerateRangeErrors()
    {
        return [
            // 範囲外（負数）
            [ '\OutOfRangeException', 0, -1 ],
            // 範囲外（整数上限超過）
            [ '\TypeError', 0, PHP_INT_MAX + 1 ],
            // 文字列型
            [ '\TypeError', 0, 'a' ],
            // 配列型
            [ '\TypeError', 0, [ 1 ] ],
            // オブジェクト型
            [ '\TypeError', 0, new \stdClass() ],
            // NULL
            [ '\TypeError', 0, null ],
        ];
    }

    /**
     * @dataProvider providerGenerateRangeErrors
     */
    public function testGenerateRangeErrors($expectedException, $start, $count)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('generateRange');

        $this->setExpectedException($expectedException);
        $mock->generateRange($start, $count);
    }

    public function providerRepeat()
    {
        return [
            // 数値型
            [ 1, 3, new \ArrayObject([ 1, 1, 1 ]) ],
            // 文字列型
            [ 'a', 3, new \ArrayObject([ 'a', 'a', 'a' ]) ],
            // 配列型
            [ [], 3, new \ArrayObject([ [], [], [] ]) ],
            // オブジェクト型
            [ new \stdClass(), 3, new \ArrayObject([ new \stdClass(), new \stdClass(), new \stdClass() ]) ],
            // NULL
            [ null, 3, new \ArrayObject([ null, null, null ]) ]
        ];
    }

    /**
     * @dataProvider providerRepeat
     */
    public function testRepeat($element, $count, $expected)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('repeat')->will($this->returnValue($expected));

        $result = $mock->repeat($element, $count);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerCast()
    {
        return [
            [ [ 1, '2', 3, '4.0', 5 ], 'int', [ 1, 2, 3, 4, 5 ] ],
            [ [ false, true, 2, 3, 4 ], 'float', [ 0.0, 1.0, 2.0, 3.0, 4.0 ] ]
        ];
    }

    /**
     * @dataProvider providerCast
     */
    public function testCast($source, $type, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('cast')->will($this->returnValue($expected));

        $result = $mock->cast(new \ArrayObject($source), $type);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }

    public function providerCastErrors()
    {
        return [
            [ '\LogicException', [ [] ], 'int' ],
            [ '\LogicException', [ new \stdClass() ], 'float' ]
        ];
    }

    /**
     * @dataProvider providerCastErrors
     */
    public function testCastErrors($expectedException, $source, $type)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('cast');

        $this->setExpectedException($expectedException);
        $mock->cast(new \ArrayObject($source), $type);
    }

    public function providerOfType()
    {
        return [
            [ [ 1, '2', 3, '4.0', 5 ], 'int', [ 1, 2, 3, 4, 5 ] ],
            [ [ false, true, 2, 3, 4 ], 'float', [ 0.0, 1.0, 2.0, 3.0, 4.0 ] ],
            [ [ new \stdClass(), 1, function () {}, 0, null ], 'bool', [ true, false ] ]
        ];
    }

    /**
     * @dataProvider providerOfType
     */
    public function testOfType($source, $type, $expectedParam)
    {
        $expected = new \ArrayObject($expectedParam);

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TCollection');
        $mock->expects($this->any())->method('ofType')->will($this->returnValue($expected));

        $result = $mock->ofType(new \ArrayObject($source), $type);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }
}
