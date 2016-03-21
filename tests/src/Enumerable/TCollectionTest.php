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
        $mock->expects($this->any())->method('range')->will($this->returnValue($expected));

        $result = $mock->range($start, $count);

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
        $mock->expects($this->any())->method('range');

        $this->setExpectedException($expectedException);
        $mock->range($start, $count);
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
}
