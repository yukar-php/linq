<?php
namespace Yukar\Linq\Tests\Enumerable;

class TInspectionTest extends \PHPUnit_Framework_TestCase
{
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

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TInspection');
        $mock->expects($this->any())->method('asEnumerableOf')->will($this->returnValue($expected));

        $result = $mock->asEnumerableOf(new \ArrayObject($param));

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

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TInspection');
        $mock->expects($this->any())->method('castOf')->will($this->returnValue($expected));

        $result = $mock->castOf(new \ArrayObject($source), $type);

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
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TInspection');
        $mock->expects($this->any())->method('castOf');

        $this->setExpectedException($expectedException);
        $mock->castOf(new \ArrayObject($source), $type);
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

        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TInspection');
        $mock->expects($this->any())->method('ofTypeOf')->will($this->returnValue($expected));

        $result = $mock->ofTypeOf(new \ArrayObject($source), $type);

        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }
}
