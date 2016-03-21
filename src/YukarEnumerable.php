<?php
namespace Yukar\Linq;

use Yukar\Linq\Enumerable\TCollection;

/**
 * LINQ機能を使用できるシーケンスの生成に関する処理を提供します。
 */
final class YukarEnumerable
{
    use TCollection
    {
        emptyList as private;
        range as private _range;
        repeat as private _repeat;
    }

    /**
     * 指定した ArrayObject のオブジェクトインスタンスから生成したシーケンスを返します。
     *
     * @param \ArrayObject $source シーケンスの内容となる ArrayObject のオブジェクトインスタンス
     *
     * @return YukarLinq 引数 $source のオブジェクトインスタンスから生成したシーケンス
     */
    public static function new(\ArrayObject $source): YukarLinq
    {
        return new YukarLinq($source);
    }

    /**
     * 指定した配列から生成したシーケンスを返します。
     *
     * @param array $source シーケンスの内容となる配列
     *
     * @return YukarLinq 指定された配列から生成したシーケンス
     */
    public static function from(array $source): YukarLinq
    {
        return self::new(new \ArrayObject($source));
    }

    /**
     * 空のシーケンスを返します。
     *
     * @return YukarLinq 空のシーケンス
     */
    public static function empty(): YukarLinq
    {
        return self::new(self::emptyList());
    }

    /**
     * 指定した範囲内の整数のシーケンスを生成します。
     *
     * @param int $start シーケンス内の最初の整数の値
     * @param int $count 生成する連続した整数の数
     *
     * @return YukarLinq 連続した整数の範囲を含むシーケンス
     */
    public static function range(int $start, int $count): YukarLinq
    {
        return self::new(self::_range($start, $count));
    }

    /**
     * 繰り返される 1 つの値を含むシーケンスを生成します。
     *
     * @param mixed $element 繰り返される値
     * @param int $count 生成されたシーケンスで値を繰り返す回数
     *
     * @return YukarLinq 繰り返される値を含むシーケンス
     */
    public static function repeat($element, int $count): YukarLinq
    {
        return self::new(self::_repeat($element, $count));
    }
}
