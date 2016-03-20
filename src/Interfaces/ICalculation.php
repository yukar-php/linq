<?php
namespace Yukar\Linq\Interfaces;

/**
 * シーケンスに対する計算処理の機能を提供するためのインターフェイスです。
 */
interface ICalculation
{
    /**
     * 値のシーケンスの合計を計算します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return number シーケンスの値の合計
     */
    public function sum(\Closure $selector = null);

    /**
     * 値のシーケンスの平均値を計算します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return float 値のシーケンスの平均値
     */
    public function average(\Closure $selector = null);

    /**
     * シーケンスの各要素に対して変換関数を呼び出し、最大値を返します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return mixed シーケンスの最大値
     */
    public function max(\Closure $selector = null);

    /**
     * シーケンスの各要素に対して変換関数を呼び出し、最小値を返します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return mixed シーケンスの最小値
     */
    public function min(\Closure $selector = null);

    /**
     * 条件を満たす指定されたシーケンス内の要素の数を表す数値を返します。
     *
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return int 述語関数の条件を満たす、シーケンス内の要素数を表す数値
     */
    public function count(\Closure $predicate = null): int;

    /**
     * シーケンスにアキュムレータ関数を適用します。
     *
     * @param \Closure $func 各要素に対して呼び出すアキュムレータ関数
     *
     * @return mixed 最終的なアキュムレータ値
     */
    public function aggregate(\Closure $func);
}
