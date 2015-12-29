<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスの集計に関する処理を提供します。
 */
trait TSet
{
    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの差集合を生成します。
     *
     * @param \ArrayObject $first $second には含まれていない要素を含むシーケンス
     * @param \ArrayObject $second 最初のシーケンスにも含まれ、返されたシーケンスからは削除される要素を含むシーケンス
     *
     * @return \ArrayObject 2 つのシーケンスの要素の差集合が格納されているシーケンス
     */
    public function except(\ArrayObject $first, \ArrayObject $second): \ArrayObject
    {
        return $this->newArrayObject(array_diff($first->getArrayCopy(), $second->getArrayCopy()));
    }

    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの積集合を生成します。
     *
     * @param \ArrayObject $first $second にも含まれる一意の要素を含むシーケンス
     * @param \ArrayObject $second 最初のシーケンスにも含まれる、返される一意の要素を含むシーケンス
     *
     * @return \ArrayObject 2 つのシーケンスの積集合を構成する要素が格納されているシーケンス
     */
    public function intersect(\ArrayObject $first, \ArrayObject $second): \ArrayObject
    {
        return $this->newArrayObject(array_intersect($first->getArrayCopy(), $second->getArrayCopy()));
    }

    /**
     * 既定の等値比較子を使用して、2 つのシーケンスの和集合を生成します。
     *
     * @param \ArrayObject $first 和集合の最初のセットを形成する一意の要素を含むシーケンス
     * @param \ArrayObject $second 和集合の 2 番目のセットを形成する一意の要素を含むシーケンス
     *
     * @return \ArrayObject 2 つの入力シーケンスの要素 (重複する要素は除く) を格納しているシーケンス
     */
    public function union(\ArrayObject $first, \ArrayObject $second): \ArrayObject
    {
        return $this->newArrayObject(array_unique($this->concat($first, $second)->getArrayCopy()));
    }

    /**
     * 2 つのシーケンスを連結します。
     *
     * @param \ArrayObject $first 連結する最初のシーケンス
     * @param \ArrayObject $second 最初のシーケンスに連結するシーケンス
     *
     * @return \ArrayObject 2 つの入力シーケンスの連結された要素が格納されているシーケンス
     */
    public function concat(\ArrayObject $first, \ArrayObject $second): \ArrayObject
    {
        return $this->newArrayObject(array_merge($first->getArrayCopy(), $second->getArrayCopy()));
    }

    /**
     * 2 つのシーケンスの対応する要素に対して、1 つの指定した関数を適用し、結果として 1 つのシーケンスを生成します。
     *
     * @param \ArrayObject $first マージする 1 番目のシーケンス
     * @param \ArrayObject $second マージする 2 番目のシーケンス
     * @param \Closure $resultSelector 2 つのシーケンスの要素をマージする方法を指定する関数
     *
     * @return \ArrayObject 2 つの入力シーケンスのマージされた要素が格納されているシーケンス
     */
    public function zip(\ArrayObject $first, \ArrayObject $second, \Closure $resultSelector): \ArrayObject
    {
        $zipped = new \ArrayObject([]);
        $size = ($first->count() > $second->count()) ? $second->count() : $first->count();

        for ($i = 0; $i < $size; $i++) {
            $zipped->append($resultSelector($first->offsetGet($i), $second->offsetGet($i)));
        }

        return $zipped;
    }

    /**
     * 要素の型に対して既定の等値比較子を使用して要素を比較することで、2 つのシーケンスが等しいかどうかを判断します。
     *
     * @param \ArrayObject $first  $second と比較するシーケンス
     * @param \ArrayObject $second 最初のシーケンスと比較するシーケンス
     *
     * @return bool 2 つのソースシーケンスが同じ長さで、それらに対応する要素が等しい場合は true。
     * それ以外の場合は false。
     */
    public function sequenceEqual(\ArrayObject $first, \ArrayObject $second): bool
    {
        if ($first->count() != $second->count()) {
            return false;
        }

        return ($this->except($first, $second)->count() === 0);
    }

    private function newArrayObject(array $object)
    {
        return new \ArrayObject(array_values($object));
    }
}
