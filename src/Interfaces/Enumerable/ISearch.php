<?php
namespace Yukar\Linq\Interfaces\Enumerable;

/**
 * シーケンスの検索に関する機能を提供するためのインターフェイスです。
 */
interface ISearch
{
    /**
     * シーケンスのすべての要素が条件を満たしているかどうかを判断します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return bool シーケンスのすべての要素がテストに合格する場合は true。それ以外の場合は false。
     */
    public function all(\Closure $predicate): bool;

    /**
     * シーケンスの任意の要素が条件を満たしているかどうかを判断します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return bool シーケンスの要素がテストに合格する場合は true。それ以外の場合は false。
     */
    public function any(\Closure $predicate): bool;

    /**
     * 指定した要素がシーケンスに含まれているかどうかを判断します。
     *
     * @param mixed $value シーケンス内で検索する値
     *
     * @return bool 指定した値を持つ要素がシーケンスに含まれている場合は true。それ以外は false。
     */
    public function contains($value): bool;

    /**
     * シーケンス内の指定されたインデックス位置にある要素を返します。
     *
     * @param int $index 取得する要素の 0 から始まるインデックス
     *
     * @return mixed シーケンス内の指定された位置にある要素
     */
    public function elementAt(int $index);

    /**
     * 指定された条件を満たすシーケンスの最初の要素を返します。
     *
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 指定された述語関数でテストに合格するシーケンスの最初の要素
     */
    public function first(\Closure $predicate = null);

    /**
     * 指定された条件を満たすシーケンスの最後の要素を返します。
     *
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 指定された述語関数でテストに合格するシーケンスの最後の要素
     */
    public function last(\Closure $predicate = null);

    /**
     * 条件を満たす、シーケンスの唯一の要素を返します。そのような要素が複数存在する場合は、例外をスローします。
     *
     * @param \Closure $predicate 要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 条件を満たす入力シーケンスの 1 つの要素
     */
    public function single(\Closure $predicate);
}
