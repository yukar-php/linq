<?php
namespace Yukar\Linq\Tests\Collections;

use Yukar\Linq\Collections\DictionaryIterator;
use Yukar\Linq\Collections\KeyValuePair;

class DictionaryIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function providerInitialize()
    {
        return [
            // 空配列
            [ [], [] ],
            // 配列
            [ [ 0 => 'a', 1 => 'b' ], [ 'a', 'b' ] ],
            // 連想配列
            [ [ 's1' => 1, 's2' => 2 ], [ 's1' => 1, 's2' => 2 ] ],
            // 辞書オブジェクト
            [ [ 'p1' => 1, 'p2' => 2 ], [ new KeyValuePair('p1', 1), new KeyValuePair('p2', 2) ] ],
        ];
    }

    /**
     * @dataProvider providerInitialize
     */
    public function testInitialize($expected, $base_list)
    {
        $this->assertSame($expected, (new DictionaryIterator($base_list))->getArrayCopy());
    }
}
