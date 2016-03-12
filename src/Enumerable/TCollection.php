<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスの要素の生成に関する処理を提供します。
 */
trait TCollection
{
    /**
     * 空のシーケンスを返します。
     *
     * @return \ArrayObject 空のシーケンス
     */
    public static function emptyList(): \ArrayObject
    {
        return new \ArrayObject([]);
    }

    /**
     * 指定した範囲内の整数のシーケンスを生成します。
     *
     * @param int $start シーケンス内の最初の整数の値
     * @param int $count 生成する連続した整数の数
     *
     * @return \ArrayObject 連続した整数の範囲を含むシーケンス
     */
    public static function range(int $start, int $count): \ArrayObject
    {
        if ($count < 0) {
            throw new \OutOfRangeException();
        }

        return new \ArrayObject(range($start, $start + $count - 1));
    }

    /**
     * 繰り返される 1 つの値を含むシーケンスを生成します。
     *
     * @param mixed $element 繰り返される値
     * @param int $count 生成されたシーケンスで値を繰り返す回数
     *
     * @return \ArrayObject 繰り返される値を含むシーケンス
     */
    public static function repeat($element, int $count): \ArrayObject
    {
        return new \ArrayObject(array_fill(0, $count, $element));
    }
}
