<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスの要素の操作に関する処理を提供します。
 */
trait TInspection
{
    /**
     * シーケンスのコピーを返します。
     *
     * @param \ArrayObject $source コピー元のシーケンス
     *
     * @return \ArrayObject 入力シーケンスのコピーとなるシーケンス
     */
    public function asEnumerableOf(\ArrayObject $source): \ArrayObject
    {
        return new \ArrayObject($source->getArrayCopy());
    }

    /**
     * シーケンスの要素を、指定した型にキャストします。
     *
     * @param \ArrayObject $source 型 $type にキャストされる要素が格納されているシーケンス
     * @param string $type キャストする型の名前
     *
     * @return \ArrayObject 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス
     */
    public function castOf(\ArrayObject $source, string $type): \ArrayObject
    {
        $dst = TCollection::emptyList();

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
    public function ofTypeOf(\ArrayObject $source, string $type): \ArrayObject
    {
        $dst = TCollection::emptyList();

        foreach ($source->getIterator() as $key => $value) {
            (is_scalar($value) === true) && $dst->append(call_user_func(strtolower($type) . "val", $value));
        }

        return $dst;
    }
}
