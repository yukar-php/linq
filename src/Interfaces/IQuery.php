<?php
namespace Yukar\Linq\Interfaces;

/**
 * 射影や選択を行う機能を提供するためのインターフェイスです。
 */
interface IQuery
{
    /**
     * 要素のインデックスを組み込むことにより、シーケンスの各要素を新しいフォームに射影します。
     *
     * @param \Closure $selector 各ソース要素に適用する変換関数。
     *                           この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return IEnumerable 各要素に対して変換関数を呼び出した結果として得られる要素を含むシーケンス
     */
    public function select(\Closure $selector): IEnumerable;

    /**
     * シーケンスから一意の要素を返します。
     *
     * @return IEnumerable ソースとなるシーケンスの一意の要素を格納するシーケンス
     */
    public function distinct(): IEnumerable;

    /**
     * 述語に基づいて値のシーケンスをフィルター処理します。
     *
     * @param \Closure $predicate 各ソース要素が条件に当てはまるかどうかをテストする関数。
     *                            この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return IEnumerable 条件を満たす、入力シーケンスの要素を含むシーケンス
     */
    public function where(\Closure $predicate): IEnumerable;
}
