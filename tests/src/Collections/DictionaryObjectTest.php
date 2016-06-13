<?php
namespace Yukar\Linq\Tests\Collections;

use Yukar\Linq\Collections\DictionaryObject;
use Yukar\Linq\Collections\KeyValuePair;

class DictionaryObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIterator()
    {
        $base_list = [ 'str1' => 1 ];
        $target_key = 'str1';

        $object = new DictionaryObject($base_list);

        $iterator = $object->getIterator();
        $iterator[$target_key] = 2;

        $this->assertNotSame($object[$target_key], $iterator[$target_key]);
        $this->assertSame($base_list[$target_key], $object[$target_key]);
    }

    public function testArrayAccess()
    {
        $object = new DictionaryObject();
        $object['first'] = 1;

        $method_reflector = (new \ReflectionClass($object))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertSame($method_reflector->invoke($object)->getArrayCopy()[0]->getValue(), $object['first']);

        unset($object['first']);

        $this->assertFalse(isset($object['first']));
    }

    public function providerAdd()
    {
        $object_1 = new \stdClass();
        $object_2 = new \stdClass();
        $closure_1 = function () {
            return true;
        };
        $closure_2 = function () {
            return false;
        };

        return [
            // キーが整数値型、値が文字列型、初期化時リストなし
            [ [ new KeyValuePair(1, 'first') ], [], 1, 'first' ],
            // キーが整数値型、値が文字列型、初期化時リストあり
            [ [ new KeyValuePair(0, 'based'), new KeyValuePair(1, 'first') ], [ 'based' ], 1, 'first' ],
            // キーが文字列型、値が数値型、初期化時リストなし
            [ [ new KeyValuePair('first', 1) ], [], 'first', 1 ],
            // キーが文字列型、値が数値型、初期化時リストあり
            [ [ new KeyValuePair('based', 0), new KeyValuePair('first', 1) ], [ 'based' => 0 ], 'first', 1 ],
            // キーが整数値型、値がオブジェクト型、初期化時リストなし
            [ [ new KeyValuePair(1, $object_2) ], [], 1, $object_2 ],
            // キーが整数値型、値がオブジェクト型、初期化時リストあり
            [ [ new KeyValuePair(0, $object_1), new KeyValuePair(1, $object_2) ], [ $object_1 ], 1, $object_2 ],
            // キーが文字列型、値がクロージャ型、初期化時リストなし
            [ [ new KeyValuePair('first', $closure_2) ], [], 'first', $closure_2 ],
            // キーが文字列型、値がクロージャ型、初期化時リストあり
            [ [ new KeyValuePair('based', $closure_1), new KeyValuePair('first', $closure_2) ], [ 'based' => $closure_1 ], 'first', $closure_2 ],
        ];
    }

    /**
     * @dataProvider providerAdd
     */
    public function testAdd($expected, $base_list, $key, $value)
    {
        $object = new DictionaryObject($base_list);
        $object->add($key, $value);

        $method_reflector = (new \ReflectionClass($object))->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($expected, $method_reflector->invoke($object)->getArrayCopy());
    }

    public function providerAddFailure()
    {
        return [
            // 追加時にエラー
            [ '\InvalidArgumentException', [ 1 => 'str' ], 'str2', 2 ],
            // 初期化時にエラー
            [ '\InvalidArgumentException', [ 1 => 'str', 'str2' => 2 ], 3, 'str3' ],
        ];
    }

    /**
     * @dataProvider providerAddFailure
     */
    public function testAddFailure($expected, $base_list, $key, $value)
    {
        $this->expectException($expected);
        (new DictionaryObject($base_list))->add($key, $value);
    }

    public function providerContainsKey()
    {
        return [
            [ true, [ 'str1' => 1, 'str2' => 2 ], 'str1' ],
            [ false, [ 'str1' => 1, 'str2' => 2 ], 'str3' ],
        ];
    }

    /**
     * @dataProvider providerContainsKey
     */
    public function testContainsKey($expected, $base_list, $key)
    {
        $this->assertSame($expected, (new DictionaryObject($base_list))->containsKey($key));
    }

    public function providerContainsValue()
    {
        return [
            [ true, [ 'str1' => 1, 'str2' => 2 ], 2 ],
            [ false, [ 'str1' => 1, 'str2' => 2 ], 3 ],
        ];
    }

    /**
     * @dataProvider providerContainsValue
     */
    public function testContainsValue($expected, $base_list, $value)
    {
        $this->assertSame($expected, (new DictionaryObject($base_list))->containsValue($value));
    }

    public function testGetKeys()
    {
        $base_list = [ 'key1' => 1, 'key2' => 2, 'key3' => 3 ];
        $expected = [ 'key1', 'key2', 'key3' ];

        $this->assertSame($expected, (new DictionaryObject($base_list))->getKeys());
    }

    public function testGetValues()
    {
        $base_list = [ 'key1' => 1, 'key2' => 2, 'key3' => 3 ];
        $expected = [ 1, 2, 3 ];

        $this->assertSame($expected, (new DictionaryObject($base_list))->getValues());
    }

    public function providerRemove()
    {
        return [
            [ [ true ], [ new KeyValuePair('str2', 2) ], [ 'str1' => 1, 'str2' => 2 ], [ 'str1' ] ],
            [ [ false ], [ new KeyValuePair('str1', 1), new KeyValuePair('str2', 2) ], [ 'str1' => 1, 'str2' => 2 ], [ 'str3' ] ],
            [ [ true, true ], [], [ 'str1' => 1, 'str2' => 2 ], [ 'str1', 'str2' ] ],
            [ [ true, false ], [ new KeyValuePair('str2', 2) ], [ 'str1' => 1, 'str2' => 2 ], [ 'str1', 'str3' ] ],
            [ [ false, true ], [ new KeyValuePair('str2', 2) ], [ 'str1' => 1, 'str2' => 2 ], [ 'str0', 'str1' ] ],
            [ [ false, false ], [ new KeyValuePair('str1', 1), new KeyValuePair('str2', 2) ], [ 'str1' => 1, 'str2' => 2 ], [ 'str3', 'str4' ] ],
        ];
    }

    /**
     * @dataProvider providerRemove
     */
    public function testRemove($expected, $after_list, $base_list, $keys)
    {
        $object = new DictionaryObject($base_list);

        foreach ($keys as $index => $key) {
            $this->assertSame($expected[$index], $object->remove($key));
        }

        $reflector = new \ReflectionClass($object);
        $method_reflector = $reflector->getMethod('getSourceList');
        $method_reflector->setAccessible(true);

        $this->assertEquals($after_list, $method_reflector->invoke($object)->getArrayCopy());
    }

    public function providerTryGetValue()
    {
        return [
            [ true, 1, [ 'str1' => 1 ], 'str1' ],
            [ false, null, [ 'str1' => 1 ], 'str2' ],
            [ true, 2, [ 'str1' => 1, 'str2' => 2 ], 'str2' ],
            [ false, null, [ 'str1' => 1, 'str2' => 2 ], 'str3' ],
        ];
    }

    /**
     * @dataProvider providerTryGetValue
     */
    public function testTryGetValue($expected, $expected_value, $base_list, $key)
    {
        $value = null;

        $this->assertSame($expected, (new DictionaryObject($base_list))->tryGetValue($key, $value));
        $this->assertSame($expected_value, $value);
    }
}
