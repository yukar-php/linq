<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスに対する計算処理の機能を提供します。
 */
trait TCalculation
{
    use TSearch
    {
        allOf as private;
        firstOf as private;
        elementAtOf as private;
    }

    /**
     * 値のシーケンスの合計を計算します。
     *
     * @param \ArrayObject $source 合計を計算する対象となる値のシーケンス
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return number シーケンスの値の合計
     */
    public function sumOf(\ArrayObject $source, \Closure $selector = null)
    {
        if ($this->allOf($source, $this->getIsScalarClosure()) === false) {
            throw new \UnexpectedValueException();
        }

        return array_sum($this->getSelectedArrayObject($source, $selector)->getArrayCopy());
    }

    /**
     * 値のシーケンスの平均値を計算します。
     *
     * @param \ArrayObject $source 平均値計算の対象となる値のシーケンス
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return float 値のシーケンスの平均値
     */
    public function averageOf(\ArrayObject $source, \Closure $selector = null)
    {
        return $this->sumOf($source, $selector) / $source->count();
    }

    /**
     * シーケンスの各要素に対して変換関数を呼び出し、最大値を返します。
     *
     * @param \ArrayObject $source 最大値を確認する対象となる値のシーケンス
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return mixed シーケンスの最大値
     */
    public function maxOf(\ArrayObject $source, \Closure $selector = null)
    {
        if ($this->allOf($source, $this->getIsScalarClosure()) === false) {
            throw new \UnexpectedValueException();
        }

        return max($this->getSelectedArrayObject($source, $selector)->getArrayCopy());
    }

    /**
     * シーケンスの各要素に対して変換関数を呼び出し、最小値を返します。
     *
     * @param \ArrayObject $source 最小値を確認する対象となる値のシーケンス
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return mixed シーケンスの最小値
     */
    public function minOf(\ArrayObject $source, \Closure $selector = null)
    {
        if ($this->allOf($source, $this->getIsScalarClosure()) === false) {
            throw new \UnexpectedValueException();
        }

        return min($this->getSelectedArrayObject($source, $selector)->getArrayCopy());
    }

    /**
     * 条件を満たす指定されたシーケンス内の要素の数を表す数値を返します。
     *
     * @param \ArrayObject $source テストおよびカウントする要素が格納されているシーケンス
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return int 述語関数の条件を満たす、シーケンス内の要素数を表す数値
     */
    public function countOf(\ArrayObject $source, \Closure $predicate = null): int
    {
        return $this->getFilteredArrayObject($source, $predicate)->count();
    }

    /**
     * シーケンスにアキュムレータ関数を適用します。
     *
     * @param \ArrayObject $source 集計対象のシーケンス
     * @param \Closure $func 各要素に対して呼び出すアキュムレータ関数
     *
     * @return mixed 最終的なアキュムレータ値
     */
    public function aggregateOf(\ArrayObject $source, \Closure $func)
    {
        $value = $this->firstOf($source);

        for ($i = 1; $i < $source->count(); $i++) {
            $value = $func($value, $this->elementAtOf($source, $i));
        }

        return $value;
    }

    private function getSelectedArrayObject(\ArrayObject $source, \Closure $selector = null): \ArrayObject
    {
        return ($selector instanceof \Closure) ? $this->selectOf($source, $selector) : $source;
    }

    private function getFilteredArrayObject(\ArrayObject $source, \Closure $predicate = null): \ArrayObject
    {
        return ($predicate instanceof \Closure) ? $this->whereOf($source, $predicate) : $source;
    }

    private function getIsScalarClosure(): \Closure
    {
        return function ($v) {
            return is_scalar($v);
        };
    }
}
