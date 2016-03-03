<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスの要素に関する処理を提供します。
 */
trait TCollection
{
    /**
     * 空のシーケンスを返します。
     *
     * @return \ArrayObject 空のシーケンス
     */
    public function emptyList(): \ArrayObject
    {
        return new \ArrayObject([]);
    }

    /**
     * シーケンスのコピーを返します。
     *
     * @param \ArrayObject $source コピー元のシーケンス
     *
     * @return \ArrayObject 入力シーケンスのコピーとなるシーケンス
     */
    public function asEnumerable(\ArrayObject $source): \ArrayObject
    {
        return new \ArrayObject($source->getArrayCopy());
    }

    /**
     * 指定した範囲内の整数のシーケンスを生成します。
     *
     * @param int $start シーケンス内の最初の整数の値
     * @param int $count 生成する連続した整数の数
     *
     * @return \ArrayObject 連続した整数の範囲を含むシーケンス
     */
    public function range(int $start, int $count): \ArrayObject
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
    public function repeat($element, int $count): \ArrayObject
    {
        return new \ArrayObject(array_fill(0, $count, $element));
    }

    /**
     * シーケンスの要素を、指定した型にキャストします。
     *
     * @param \ArrayObject $source 型 $type にキャストされる要素が格納されているシーケンス
     * @param string $type キャストする型の名前
     *
     * @return \ArrayObject 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス
     */
    public function cast(\ArrayObject $source, string $type): \ArrayObject
    {
        $dst = $this->emptyList();

        foreach ($source->getIterator() as $key => $value) {
            if (is_scalar($value) === false) {
                throw new \LogicException();
            }

            $dst->append(call_user_func(strtolower($type) . "val", $value));
        }

        return $dst;
    }

    /**
     * 指定された型に基づいてシーケンスの要素をフィルター処理します。
     *
     * @param \ArrayObject $source フィルター処理する要素を含むシーケンス
     * @param string $type シーケンスの要素をフィルター処理する型の名前
     *
     * @return \ArrayObject 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス。
     *                      キャストに失敗した要素は含まれません。
     */
    public function ofType(\ArrayObject $source, string $type): \ArrayObject
    {
        $dst = $this->emptyList();

        foreach ($source->getIterator() as $key => $value) {
            (is_scalar($value) === true) && $dst->append(call_user_func(strtolower($type) . "val", $value));
        }

        return $dst;
    }
}
