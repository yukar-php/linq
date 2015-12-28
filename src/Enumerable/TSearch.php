<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスの検索に関する機能を提供します。
 */
trait TSearch
{
    use TQuery { where as private; }

    /**
     * シーケンスのすべての要素が条件を満たしているかどうかを判断します。
     *
     * @param \ArrayObject $source 述語を適用する要素を格納している ArrayObject
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return bool シーケンスのすべての要素がテストに合格する場合は true。それ以外の場合は false。
     */
    public function all(\ArrayObject $source, \Closure $predicate): bool
    {
        return $this->where($source, $predicate)->count() === $source->count();
    }

    /**
     * シーケンスの任意の要素が条件を満たしているかどうかを判断します。
     *
     * @param \ArrayObject $source 述語を適用する要素を格納している ArrayObject
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return bool シーケンスの要素がテストに合格する場合は true。それ以外の場合は false。
     */
    public function any(\ArrayObject $source, \Closure $predicate): bool
    {
        return ($this->where($source, $predicate)->count() > 0);
    }

    /**
     * 指定した要素がシーケンスに含まれているかどうかを判断します。
     *
     * @param \ArrayObject $source 値の検索対象となるシーケンス
     * @param mixed $value シーケンス内で検索する値
     *
     * @return bool 指定した値を持つ要素がシーケンスに含まれている場合は true。それ以外は false。
     */
    public function contains(\ArrayObject $source, $value): bool
    {
        return in_array($value, $source->getArrayCopy());
    }

    /**
     * シーケンス内の指定されたインデックス位置にある要素を返します。
     *
     * @param \ArrayObject $source 返される要素が含まれるシーケンス
     * @param int $index 取得する要素の 0 から始まるインデックス
     *
     * @return mixed シーケンス内の指定された位置にある要素
     */
    public function elementAt(\ArrayObject $source, int $index)
    {
        if ($index < 0 || $index >= $source->count()) {
            throw new \OutOfRangeException();
        }

        return $source->offsetGet($index);
    }

    /**
     * 指定された条件を満たすシーケンスの最初の要素を返します。
     *
     * @param \ArrayObject $source 返される要素が含まれるシーケンス
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 指定された述語関数でテストに合格するシーケンスの最初の要素
     */
    public function first(\ArrayObject $source, \Closure $predicate = null)
    {
        $target = $this->getArrayObject($source, $predicate)->getArrayCopy();

        return array_shift($target);
    }

    /**
     * 指定された条件を満たすシーケンスの最後の要素を返します。
     *
     * @param \ArrayObject $source 返される要素が含まれるシーケンス
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 指定された述語関数でテストに合格するシーケンスの最後の要素
     */
    public function last(\ArrayObject $source, \Closure $predicate = null)
    {
        $target = $this->getArrayObject($source, $predicate)->getArrayCopy();

        return array_pop($target);
    }

    /**
     * 条件を満たす、シーケンスの唯一の要素を返します。そのような要素が複数存在する場合は、例外をスローします。
     *
     * @param \ArrayObject $source 1 つの要素を返すシーケンス
     * @param \Closure $predicate 要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 条件を満たす入力シーケンスの 1 つの要素
     */
    public function single(\ArrayObject $source, \Closure $predicate)
    {
        $result = $this->where($source, $predicate);

        if ($result->count() !== 1) {
            throw new \LogicException();
        }

        return $result[0];
    }

    private function getArrayObject(\ArrayObject $source, \Closure $predicate = null): \ArrayObject
    {
        return ($predicate instanceof \Closure) ? $this->where($source, $predicate) : $source;
    }
}
