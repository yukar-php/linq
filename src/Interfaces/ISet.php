<?php
namespace Yukar\Linq\Interfaces;

use Yukar\Linq\YukarLinq;

/**
 * シーケンスの集計に関する処理を提供するためのインターフェイスです。
 */
interface ISet
{
    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの差集合を生成します。
     *
     * @param YukarLinq $second 最初のシーケンスにも含まれ、返されたシーケンスからは削除される要素を含むシーケンス
     *
     * @return YukarLinq 2 つのシーケンスの要素の差集合が格納されているシーケンス
     */
    public function except(YukarLinq $second): YukarLinq;

    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの積集合を生成します。
     *
     * @param YukarLinq $second 最初のシーケンスにも含まれる、返される一意の要素を含むシーケンス
     *
     * @return YukarLinq 2 つのシーケンスの積集合を構成する要素が格納されているシーケンス
     */
    public function intersect(YukarLinq $second): YukarLinq;

    /**
     * 既定の等値比較子を使用して、2 つのシーケンスの和集合を生成します。
     *
     * @param YukarLinq $second 和集合の 2 番目のセットを形成する一意の要素を含むシーケンス
     *
     * @return YukarLinq 2 つの入力シーケンスの要素 (重複する要素は除く) を格納しているシーケンス
     */
    public function union(YukarLinq $second): YukarLinq;

    /**
     * 2 つのシーケンスを連結します。
     *
     * @param YukarLinq $second 最初のシーケンスに連結するシーケンス
     *
     * @return YukarLinq 2 つの入力シーケンスの連結された要素が格納されているシーケンス
     */
    public function concat(YukarLinq $second): YukarLinq;

    /**
     * 2 つのシーケンスの対応する要素に対して、1 つの指定した関数を適用し、結果として 1 つのシーケンスを生成します。
     *
     * @param YukarLinq $second マージする 2 番目のシーケンス
     * @param \Closure $resultSelector 2 つのシーケンスの要素をマージする方法を指定する関数
     *
     * @return YukarLinq 2 つの入力シーケンスのマージされた要素が格納されているシーケンス
     */
    public function zip(YukarLinq $second, \Closure $resultSelector): YukarLinq;

    /**
     * 要素の型に対して既定の等値比較子を使用して要素を比較することで、2 つのシーケンスが等しいかどうかを判断します。
     *
     * @param YukarLinq $second 最初のシーケンスと比較するシーケンス
     *
     * @return bool 2 つのソースシーケンスが同じ長さで、それらに対応する要素が等しい場合は true。
     * それ以外の場合は false。
     */
    public function sequenceEqual(YukarLinq $second): bool;
}
