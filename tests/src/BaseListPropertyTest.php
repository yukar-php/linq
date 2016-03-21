<?php
namespace Yukar\Linq\Tests\Enumerable;

class BaseListPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function providerSetSourceList()
    {
        return [
            // 正常系（空）
            [ new \ArrayObject(), null ],
            // 正常系（オブジェクト）
            [ new \ArrayObject(new \stdClass()), null ],
            // 正常系（配列）
            [ new \ArrayObject([ 'a', 'b', 'c' ]), null ],
            // 異常系（NULL）
            [ null, '\TypeError' ],
            // 異常系（整数）
            [ 1, '\TypeError' ],
            // 異常系（小数点数）
            [ 0.1 , '\TypeError' ],
            // 異常系（論理型）
            [ false, '\TypeError' ],
            // 異常系（配列型）
            [ [ 'a', 'b', 'c' ], '\TypeError' ],
            // 異常系（オブジェクト型）
            [ new \stdClass(), '\TypeError' ],
            // 異常系（クロージャ）
            [ function () { return true; }, '\TypeError' ]
        ];
    }

    /**
     * @dataProvider providerSetSourceList
     */
    public function testSetSourceList($param, $expectedException)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\BaseListProperty');
        $method_invoker = (new \ReflectionClass($mock))->getMethod('setSourceList');
        $method_invoker->setAccessible(true);
                
        isset($expectedException) && $this->setExpectedException($expectedException);
        $method_invoker->invoke($mock, $param);
    }
    public function providerGetSourceList()
    {
        return [
            // 正常系（空）
            [ new \ArrayObject(), new \ArrayObject(), null ],
            // 正常系（オブジェクト）
            [ new \ArrayObject(new \stdClass()), new \ArrayObject(new \stdClass()), null ],
            // 正常系（配列）
            [ new \ArrayObject([ 'a', 'b', 'c' ]), new \ArrayObject([ 'a', 'b', 'c' ]), null ],
            // 異常系（NULL）
            [ null, null, '\LogicException' ],
        ];
    }

    /**
     * @dataProvider providerGetSourceList
     */
    public function testGetSourceList($param, $expected, $expectedException)
    {
        isset($expectedException) && $this->setExpectedException($expectedException);
        
        $mock = $this->getMockForAbstractClass('Yukar\Linq\BaseListProperty');
        $mock->expects($this->any())->method('getSourceList')->will($this->returnValue($expected));
        
        if (isset($param) === true) {
            $method_invoker = (new \ReflectionClass($mock))->getMethod('setSourceList');
            $method_invoker->setAccessible(true);
            $method_invoker->invoke($mock, $param);
        }

        $this->assertEquals($expected, $mock->getSourceList());
    }
}
