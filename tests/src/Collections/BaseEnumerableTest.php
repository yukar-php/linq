<?php
namespace Yukar\Linq\Tests\Collections;

use Yukar\Linq\Collections\BaseEnumerable;
use Yukar\Linq\Collections\DictionaryObject;
use Yukar\Linq\Collections\ListObject;

class BaseEnumerableTest extends \PHPUnit_Framework_TestCase
{
    public function providerSetSourceList()
    {
        return [
            // 正常系（空）
            [ null, [] ],
            // 正常系（オブジェクト）
            [ null, new \stdClass() ],
            // 正常系（配列）
            [ null, [ 'a', 'b', 'c' ] ],
            // 正常系（クロージャ）
            [ null, function () { return true; } ],
            // 異常系（NULL）
            [ '\InvalidArgumentException', null ],
            // 異常系（整数）
            [ '\InvalidArgumentException', 1 ],
            // 異常系（小数点数）
            [ '\InvalidArgumentException', 0.1 ],
            // 異常系（論理型）
            [ '\InvalidArgumentException', false ],
        ];
    }

    /**
     * @dataProvider providerSetSourceList
     */
    public function testSetSourceList($expectedException, ...$params)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');
        $method_invoker = (new \ReflectionClass($mock))->getMethod('setSourceList');
        $method_invoker->setAccessible(true);

        isset($expectedException) && $this->setExpectedException($expectedException);
        $method_invoker->invoke($mock, ...$params);
    }

    public function providerGetSourceList()
    {
        return [
            // 正常系（空）
            [ [], new \ArrayObject(), null ],
            // 正常系（オブジェクト）
            [ new \stdClass(), new \ArrayObject(new \stdClass()), null ],
            // 正常系（配列）
            [ [ 'a', 'b', 'c' ], new \ArrayObject([ 'a', 'b', 'c' ]), null ],
            // 正常系（NULL）
            [ null, new \ArrayObject(), null ],
        ];
    }

    /**
     * @dataProvider providerGetSourceList
     */
    public function testGetSourceList($param, $expected, $expectedException)
    {
        isset($expectedException) && $this->setExpectedException($expectedException);

        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');

        if (isset($param) === true) {
            $setter_invoker = (new \ReflectionClass($mock))->getMethod('setSourceList');
            $setter_invoker->setAccessible(true);
            $setter_invoker->invoke($mock, $param);
        }

        $getter_invoker = (new \ReflectionClass($mock))->getMethod('getSourceList');
        $getter_invoker->setAccessible(true);

        $this->assertEquals($expected, $getter_invoker->invoke($mock));
    }

    public function providerLazyEvalPassed()
    {
        return [
            [ 'sum', 6, [ 1, 2, 3 ] ],
            [ 'average', 20, [ 10, 20, 30 ] ],
            [ 'max', 10, [ -10, 0, 10 ] ],
            [ 'min', -10, [ -10, 0, 10 ] ],
            [ 'count', 6, [ 1, 2, 4, 8, 16, 32 ] ],
            [ 'aggregate', 6, [ 1, 2, 3 ], function ($r, $v) { return $r + $v; } ],

            [ 'skip', [ 3, 4, 5 ], [ 1, 2, 3, 4, 5 ], 2 ],
            [ 'skipWhile', [ 6, 9 ], [ 1, 3, 5, 6, 9 ], function ($v) { return ($v % 2 !== 0); } ],
            [ 'take', [ 1, 2 ], [ 1, 2, 3, 4, 5 ], 2 ],
            [ 'takeWhile', [ 1, 3, 5 ], [ 1, 3, 5, 6, 9 ], function ($v) { return ($v % 2 !== 0); } ],

            [ 'asEnumerable', [ 1, 2, 3 ], [ 1, 2, 3 ] ],
            [ 'cast', [ 1, 2, 3, 4, 5 ], [ 1, '2', 3, '4.0', 5 ], 'int' ],
            [ 'ofType', [ 1, 2, 4, 5 ], [ 1, '2', null, '4.0', 5 ], 'int' ],

            [ 'select', [ 1, 4, 9 ], [ 1, 2, 3 ], function ($value) { return $value * $value; } ],
            [ 'distinct', [ 1, false, [ 3 ], '4', (object)5 ], [ 1, false, [ 3 ], '4', (object)5 ] ],
            [ 'where', [ 1, 2, 3, 4, 5 ], [ 1, 2, 3, 4, 5 ], function () { return true; } ],

            [ 'all', true, [ 1, 2, 3 ], function () { return true; } ],
            [ 'any', true, [ 1, 2, 3 ], function () { return true; } ],
            [ 'contains', true,  [ 1, 2, 3 ], 1 ],
            [ 'elementAt', 2, [ 1, 2, 3 ], 1 ],
            [ 'first', 2, [ 1, 2, 3 ], function ($v) { return ($v % 2 === 0); } ],
            [ 'last', 3, [ 1, 2, 3 ], function ($v) { return ($v % 2 !== 0); } ],
            [ 'single', 2, [ 1, 2, 3 ], function ($v) { return ($v % 2 === 0); } ],

            [ 'except', [ 2 ], [ 1, 2, 3 ], new \ArrayObject([ 1, 3, 5 ]) ],
            [ 'intersect', [ 1, 3 ], [ 1, 2, 3 ], new \ArrayObject([ 1, 3, 5 ]) ],
            [ 'union', [ 1, 2, 3, 5 ], [ 1, 2, 3 ], new \ArrayObject([ 1, 3, 5 ]) ],
            [ 'concat', [ 1, 2, 3, 1, 3, 5 ], [ 1, 2, 3 ], new \ArrayObject([ 1, 3, 5 ]) ],
            [ 'zip', [ 1, 6, 15 ], [ 1, 2, 3 ], new \ArrayObject([ 1, 3, 5 ]), function ($v1, $v2) { return $v1 * $v2; } ],
            [ 'sequenceEqual', true, [ 1, 2, 3 ], new \ArrayObject([ 1, 2, 3 ]) ],
        ];
    }

    /**
     * @dataProvider providerLazyEvalPassed
     */
    public function testAddToLazyEval($invoke_method, $expected, $base_list, ...$bind_params)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');

        $method_invoker = (new \ReflectionClass($mock))->getMethod('addToLazyEval');
        $method_invoker->setAccessible(true);

        $this->assertEquals($mock, $method_invoker->invoke($mock, $invoke_method, ...$bind_params));
    }

    /**
     * @dataProvider providerLazyEvalPassed
     */
    public function testGetLazyEvalList($invoke_method, $expected, $base_list, ...$bind_params)
    {
        $expected_list = [
            function (BaseEnumerable $object) use ($invoke_method, $bind_params) {
                $reflector = new \ReflectionClass($object);

                $execute_method = $reflector->getMethod("{$invoke_method}Of");
                $execute_method->setAccessible(true);

                $getter_method = $reflector->getMethod('getSourceList');
                $getter_method->setAccessible(true);

                return $execute_method->invoke($object, $getter_method->invoke($object), ...$bind_params);
            }
        ];

        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');
        $reflector = new \ReflectionClass($mock);

        $add_invoker = $reflector->getMethod('addToLazyEval');
        $get_invoker = $reflector->getMethod('getLazyEvalList');
        $add_invoker->setAccessible(true);
        $get_invoker->setAccessible(true);
        $add_invoker->invoke($mock, $invoke_method, ...$bind_params);

        $this->assertEquals(
            $expected_list,
            $get_invoker->invoke($mock, $invoke_method, ...$bind_params)
        );
    }

    /**
     * @dataProvider providerLazyEvalPassed
     */
    public function testEvalLazy($invoke_method, $expected, $base_list, ...$bind_params)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);

        $add_invoker = $reflector->getMethod('addToLazyEval');
        $eval_invoker = $reflector->getMethod('evalLazy');
        $prop_reflector = $reflector->getParentClass()->getProperty('list_object');
        $add_invoker->setAccessible(true);
        $eval_invoker->setAccessible(true);
        $prop_reflector->setAccessible(true);
        $add_invoker->invoke($object, $invoke_method, ...$bind_params);
        $before_prop = $prop_reflector->getValue($object);
        $eval_result = $eval_invoker->invoke($object);
        $after_prop = $prop_reflector->getValue($object);

        self::assertSame($before_prop, $after_prop);

        if (is_scalar($eval_result) === false) {
            self::assertInstanceOf('Yukar\Linq\Collections\BaseEnumerable', $eval_result);

            $get_after_invoker = (new \ReflectionClass($eval_result))->getMethod('getSourceList');
            $get_after_invoker->setAccessible(true);
            $after_get = $get_after_invoker->invoke($eval_result)->getArrayCopy();

            self::assertNotEquals($before_prop, $after_get);
            self::assertEquals($expected, $after_get);
        } else {
            self::assertEquals($expected, $eval_result);
        }
    }

    public function providerAllMethodPassed()
    {
        return [
            [ 'sum', 6, [ 1, 2, 3 ] ],
            [ 'average', 20, [ 10, 20, 30 ] ],
            [ 'max', 10, [ -10, 0, 10 ] ],
            [ 'min', -10, [ -10, 0, 10 ] ],
            [ 'count', 6, [ 1, 2, 4, 8, 16, 32 ] ],
            [ 'aggregate', 6, [ 1, 2, 3 ], function ($r, $v) { return $r + $v; } ],

            [ 'skip', [ 3, 4, 5 ], [ 1, 2, 3, 4, 5 ], 2 ],
            [ 'skipWhile', [ 6, 9 ], [ 1, 3, 5, 6, 9 ], function ($v) { return ($v % 2 !== 0); } ],
            [ 'take', [ 1, 2 ], [ 1, 2, 3, 4, 5 ], 2 ],
            [ 'takeWhile', [ 1, 3, 5 ], [ 1, 3, 5, 6, 9 ], function ($v) { return ($v % 2 !== 0); } ],

            [ 'asEnumerable', [ 1, 2, 3 ], [ 1, 2, 3 ] ],
            [ 'cast', [ 1, 2, 3, 4, 5 ], [ 1, '2', 3, '4.0', 5 ], 'int' ],
            [ 'ofType', [ 1, 2, 4, 5 ], [ 1, '2', null, '4.0', 5 ], 'int' ],

            [ 'select', [ 1, 4, 9 ], [ 1, 2, 3 ], function ($value) { return $value * $value; } ],
            [ 'distinct', [ 1, false, [ 3 ], '4', (object)5 ], [ 1, false, [ 3 ], '4', (object)5 ] ],
            [ 'where', [ 1, 2, 3, 4, 5 ], [ 1, 2, 3, 4, 5 ], function () { return true; } ],

            [ 'all', true, [ 1, 2, 3 ], function () { return true; } ],
            [ 'any', true, [ 1, 2, 3 ], function () { return true; } ],
            [ 'contains', true,  [ 1, 2, 3 ], 1 ],
            [ 'elementAt', 2, [ 1, 2, 3 ], 1 ],
            [ 'first', 2, [ 1, 2, 3 ], function ($v) { return ($v % 2 === 0); } ],
            [ 'last', 3, [ 1, 2, 3 ], function ($v) { return ($v % 2 !== 0); } ],
            [ 'single', 2, [ 1, 2, 3 ], function ($v) { return ($v % 2 === 0); } ],

            [ 'except', [ 2 ], [ 1, 2, 3 ], new ListObject([ 1, 3, 5 ]) ],
            [ 'intersect', [ 1, 3 ], [ 1, 2, 3 ], new ListObject([ 1, 3, 5 ]) ],
            [ 'union', [ 1, 2, 3, 5 ], [ 1, 2, 3 ], new ListObject([ 1, 3, 5 ]) ],
            [ 'concat', [ 1, 2, 3, 1, 3, 5 ], [ 1, 2, 3 ], new ListObject([ 1, 3, 5 ]) ],
            [ 'zip', [ 1, 6, 15 ], [ 1, 2, 3 ], new ListObject([ 1, 3, 5 ]), function ($v1, $v2) { return $v1 * $v2; } ],
            [ 'sequenceEqual', true, [ 1, 2, 3 ], new ListObject([ 1, 2, 3 ]) ],
        ];
    }

    /**
     * @dataProvider providerAllMethodPassed
     */
    public function testAllMethodPassed($invoke_method, $expected, $base_list, ...$bind_params)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);

        $reflector = (new \ReflectionClass($object))->getMethod($invoke_method);
        $reflector->setAccessible(true);
        $invoke_result = $reflector->invoke($object, ...$bind_params);

        $this->assertEquals(
            is_array($expected) ? (new \ReflectionClass($mock))->newInstance($expected) : $expected,
            ($invoke_result instanceof BaseEnumerable) ? $invoke_result() : $invoke_result
        );
    }

    public function providerConvertMethodPassed()
    {
        return [
            [ 'toArray', [ 1, 2, 3, 4, 5 ], [ 1, 2, 3, 4, 5 ] ],
            [ 'toList', new ListObject([ 1, 2, 3, 4, 5 ]), [ 1, 2, 3, 4, 5 ] ],
            [
                'toDictionary',
                new DictionaryObject([ 'selector_str1' => 1, 'selector_str2' => 2 ]),
                [ 'str1' => 1, 'str2' => 2 ],
                function ($key) {
                    return "selector_{$key}";
                }
            ],
            [
                'toDictionary',
                new DictionaryObject([ 'selector_str1' => 2, 'selector_str2' => 4 ]),
                [ 'str1' => 1, 'str2' => 2 ],
                function ($key) {
                    return "selector_{$key}";
                },
                function ($value) {
                    return $value * 2;
                }
            ],
        ];
    }

    /**
     * @dataProvider providerConvertMethodPassed
     */
    public function testConvertMethodPassed($invoke_method, $expected, $base_list, ...$bind_params)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);

        $reflector = (new \ReflectionClass($object))->getMethod($invoke_method);
        $invoke_result = $reflector->invoke($object, ...$bind_params);

        $this->assertEquals($expected, $invoke_result);
    }

    public function providerEvalLazyChain()
    {
        return [
            [
                [ 'where', 'select', 'sum' ],
                12,
                [ 1, 2, 3, 4, 5 ],
                [
                    'where' => [ function ($v) { return $v % 2 === 0; } ],
                    'select' => [ function ($v) { return $v * 2; } ],
                    'sum' => [  ]
                ]
            ],
            [
                [ 'where', 'select', 'toArray' ],
                [ 4, 8 ],
                [ 1, 2, 3, 4, 5 ],
                [
                    'where' => [ function ($v) { return $v % 2 === 0; } ],
                    'select' => [ function ($v) { return $v * 2; } ],
                    'toArray' => [  ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerEvalLazyChain
     */
    public function testEvalLazyChain($invoke_methods, $expected, $base_list, $bind_params)
    {
        $mock = $this->getMockForAbstractClass('Yukar\Linq\Collections\BaseEnumerable');
        $object = (new \ReflectionClass($mock))->newInstance($base_list);
        $reflector = new \ReflectionClass($object);
        $invoke_result = null;

        foreach ($invoke_methods as $key => $invoke_method) {
            $method_reflector = $reflector->getMethod($invoke_method);
            $method_reflector->setAccessible(true);
            $invoke_result = $method_reflector->invoke($object, ...$bind_params[$invoke_method]);

            is_object($invoke_result) && $reflector = new \ReflectionClass($invoke_result);
        }

        $this->assertEquals($expected, $invoke_result);
    }
}
