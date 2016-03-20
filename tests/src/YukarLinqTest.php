<?php
namespace Yukar\Linq\Tests\Enumerable;

use Yukar\Linq\YukarEnumerable;

class YukarLinqTest extends \PHPUnit_Framework_TestCase
{
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
            
            [ 'except', [ 2 ], [ 1, 2, 3 ], YukarEnumerable::from([ 1, 3, 5 ]) ],
            [ 'intersect', [ 1, 3 ], [ 1, 2, 3 ], YukarEnumerable::from([ 1, 3, 5 ]) ],
            [ 'union', [ 1, 2, 3, 5 ], [ 1, 2, 3 ], YukarEnumerable::from([ 1, 3, 5 ]) ],
            [ 'concat', [ 1, 2, 3, 1, 3, 5 ], [ 1, 2, 3 ], YukarEnumerable::from([ 1, 3, 5 ]) ],
            [ 'zip', [ 1, 6, 15 ], [ 1, 2, 3 ], YukarEnumerable::from([ 1, 3, 5 ]), function ($v1, $v2) { return $v1 * $v2; } ],
        ];
    }

    /**
     * @dataProvider providerAllMethodPassed
     */
    public function testAllMethodPassed($invoke_method, $expected, $base_list, ...$bind_params)
    {
        $object = YukarEnumerable::from($base_list);

        $this->assertEquals(
            is_array($expected) ? YukarEnumerable::from($expected) : $expected,
            (new \ReflectionClass($object))->getMethod($invoke_method)->invokeArgs($object, $bind_params)
        );
    }
}
