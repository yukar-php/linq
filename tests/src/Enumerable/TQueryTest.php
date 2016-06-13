<?php
namespace Yukar\Linq\Tests\Enumerable;

class TQueryTest extends \PHPUnit_Framework_TestCase
{
    public function providerSelect()
    {
        return [
            // スカラー型整数
            [ [ 1, 2, 3 ], new \ArrayObject([ 1, 4, 9 ]), function ($value) { return $value * $value; } ],
            // スカラー型小数点数
            [ [ 1, 2, 3 ], new \ArrayObject([ 1.5, 3.0, 4.5 ]), function ($value) { return $value * 1.5; } ],
            // スカラー型論理型
            [ [ 1, 2, 3 ], new \ArrayObject([ false, true, false ]), function ($value) { return ($value % 2 === 0); } ],
            // 配列型
            [ [ 1, 2, 3 ], new \ArrayObject([ [ 1 ], [ 2 ], [ 3 ] ]), function ($value) { return [ $value ]; } ],
            // オブジェクト型
            [ [ 1, 2, 3 ], new \ArrayObject([ (object)1, (object)2, (object)3 ]), function ($value) { return (object)$value; } ],
        ];
    }
    
    /**
     * @dataProvider providerSelect
     */
    public function testSelect($param, $expected, $selector)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TQuery');

        $result = $mock->selectOf(new \ArrayObject($param), $selector);
        
        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected->getArrayCopy(), $result->getArrayCopy());
    }
    
    public function providerSelectErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\TypeError', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\TypeError', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\TypeError', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 両方異常値（無名クラスとオブジェクト型）
            [ '\TypeError', new class {}, new \ArrayObject([ 1, 2, 3, 4, 5 ]) ],
            // 片方正常値・片方異常値（ArrayObjectとスカラー型数値）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), 1 ],
            // 片方正常値・片方異常値（スカラー型数値とクロージャ）
            [ '\TypeError', 0.0, function () { return true; } ],
        ];
    }

    /**
     * @dataProvider providerSelectErrors
     */
    public function testSelectErrors($expectedException, $source, $selector)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TQuery');

        $this->setExpectedException($expectedException);
        $mock->selectOf($source, $selector);
    }
    
    public function providerDistinct()
    {
        return [
            // 内容が全て異なる
            [ [ 1, false, [ 3 ], '4', (object)5 ], new \ArrayObject([ 1, false, [ 3 ], '4', (object)5 ]) ],
            // 内容が全て同じ
            [ [ 1, 1, 1 ], new \ArrayObject([ 1 ]) ],
            // 内容が二種類の値
            [ [ 1, 'a', 1, 'a' ], new \ArrayObject([ 1, 'a' ]) ],
            // 同一と扱われる値の集合
            [ [ 0, false, 0.0, '0' ], new \ArrayObject([ 0 ]) ],
            // スカラー型（整数と小数点数の混合）
            [ [ 1, 2.0, 1.1, 2 ], new \ArrayObject([ 1, 2.0, 1.1 ]) ],
            // 文字列型
            [ [ 'a', '!', '%', '!', '%' ], new \ArrayObject([ 'a', '!', '%' ]) ],
            // 文字列型（数値文字列と数値の混合）
            [ [ '1', 0, '2.0', 2, 1 ], new \ArrayObject([ '1', 0, '2.0' ]) ],
            // 論理型
            [ [ true, false, true, false, true ], new \ArrayObject([ true, false ]) ],
            // 配列
            [ [ [ 1 ], [], [ 2 ], [ 1 ], [] ], new \ArrayObject([ [ 1 ], [], [ 2 ] ]) ],
            // オブジェクト
            [ [ (object)1, (object)'z', (object)false ], new \ArrayObject([ (object)1, (object)'z', (object)false ]) ],
            // NULL
            [ [ null, null, null ], new \ArrayObject([ null ]) ],
        ];
    }

    /**
     * @dataProvider providerDistinct
     */
    public function testDistinct($param, $expected)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TQuery');

        $result = $mock->distinctOf(new \ArrayObject($param));
        
        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected->getArrayCopy(), $result->getArrayCopy());
    }
    
    public function providerDistinctErrors()
    {
        return [
            // 異常値（NULL）
            [ '\TypeError', null ],
            // 異常値（スカラー型整数）
            [ '\TypeError', 0 ],
            // 異常値（スカラー型小数点数）
            [ '\TypeError', 2.1 ],
            // 異常値（スカラー型論理値）
            [ '\TypeError', true ],
            // 異常値（配列型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ] ],
            // 異常値（オブジェクト型）
            [ '\TypeError', new \stdClass() ],
            // 両方異常値（クロージャ）
            [ '\TypeError', function () { return false; } ],
            // 両方異常値（無名クラス）
            [ '\TypeError', new class {} ],
        ];
    }

    /**
     * @dataProvider providerDistinctErrors
     */
    public function testDistinctErrors($expectedException, $source)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TQuery');

        $this->setExpectedException($expectedException);
        $mock->distinctOf($source);
    }
    
    public function providerWhere()
    {
        return [
            [ [ 1, 2, 3, 4, 5 ], new \ArrayObject([ 1, 2, 3, 4, 5 ]), function () { return true; } ],
            [ [ 1, 2, 3, 4, 5 ], new \ArrayObject([]), function () { return false; } ],
            [ [ 1, 2, 3, 4, 5 ], new \ArrayObject([ 2, 4 ]), function ($value) { return ($value % 2 === 0); } ],
            [ [ 1, 2, 3, 4, 5 ], new \ArrayObject([ 1, 3, 5 ]), function ($value) { return ($value % 2 !== 0); } ],
        ];
    }

    /**
     * @dataProvider providerWhere
     */
    public function testWhere($param, $expected, $predicate)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TQuery');

        $result = $mock->whereOf(new \ArrayObject($param), $predicate);
        
        $this->assertInstanceOf('\ArrayObject', $result);
        $this->assertCount($expected->count(), $result);
        $this->assertEquals($expected, $result);
    }
    
    public function providerWhereErrors()
    {
        return [
            // 両方異常値（NULL）
            [ '\TypeError', null, null ],
            // 両方異常値（スカラー型数値とスカラー型論理値）
            [ '\TypeError', 0, false ],
            // 両方異常値（スカラー型論理値と配列）
            [ '\TypeError', true, [ 1, 2, 3 ] ],
            // 両方異常値（配列型とオブジェクト型）
            [ '\TypeError', [ 1, 2, 3, 4, 5 ], new \stdClass() ],
            // 片方正常値・片方異常値（ArrayObjectとスカラー型数値）
            [ '\TypeError', new \ArrayObject([ 1, 2, 3, 4, 5 ]), 1 ],
            // 片方正常値・片方異常値（スカラー型数値とクロージャ）
            [ '\TypeError', 0.0, function ($value) { return true; } ],
            // 入力値と期待値の不一致（同一）
            [ null, new \ArrayObject([ 1, 2, 3 ]), function () { return false; }, new \ArrayObject([ 1, 2, 3 ]) ],
            // 入力値と期待値の不一致（増加）
            [ null, new \ArrayObject([ 1 ]), function () { return false; }, new \ArrayObject([ 1, 2, 3 ]) ],
            // 入力値と期待値の不一致（減少）
            [ null, new \ArrayObject([ 1, 2, 3 ]), function () { return false; }, new \ArrayObject([ 1 ]) ],
        ];
    }

    /**
     * @dataProvider providerWhereErrors
     */
    public function testWhereErrors($expectedException, $source, $predicate, $expected = null)
    {
        $mock = $this->getMockForTrait('Yukar\Linq\Enumerable\TQuery');

        $this->setExpectedException($expectedException);
        $result = $mock->whereOf($source, $predicate);
        
        if ($expectedException !== null) {
            $this->assertInstanceOf('\ArrayObject', $result);
            $this->assertNotCount($expected->count(), $result);
            $this->assertNotEquals($expected, $result);
        }
    }
}
