<?php
namespace Yukar\Linq\Interfaces;

use Yukar\Linq\YukarLinq;

/**
 * シーケンスの要素の抽出に関する処理を提供するためのインターフェイスです。
 */
interface IExtract
{
    /**
     * シーケンス内の指定された数の要素をバイパスし、残りの要素を返します。
     *
     * @param int $count 残りの要素を返す前にスキップする要素の数
     *
     * @return \ArrayObject 入力シーケンスで指定されたインデックスの後に出現する要素を含むシーケンス
     */
    public function skip(int $count): YukarLinq;

    /**
     * 指定された条件が満たされる限り、シーケンスの要素をバイパスした後、残りの要素を返します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return \ArrayObject テストに初めて合格しない要素から入力シーケンスの最後の要素までのシーケンス
     */
    public function skipWhile(\Closure $predicate): YukarLinq;

    /**
     * シーケンスの先頭から、指定された数の連続する要素を返します。
     *
     * @param int $count 返す要素数
     *
     * @return \ArrayObject 入力シーケンスの先頭から、指定された数の要素を含むシーケンス
     */
    public function take(int $count): YukarLinq;

    /**
     * 指定された条件が満たされる限り、シーケンスから要素を返します
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return \ArrayObject 入力シーケンスの先頭の要素からテストに初めて合格しない要素の前に出現する要素までを含むシーケンス
     */
    public function takeWhile(\Closure $predicate): YukarLinq;
}
