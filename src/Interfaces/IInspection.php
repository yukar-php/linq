<?php
namespace Yukar\Linq\Interfaces;

use Yukar\Linq\YukarLinq;

/**
 * シーケンスの要素の操作に関する処理を提供するためのインターフェイスです。
 */
interface IInspection
{
    /**
     * シーケンスのコピーを返します。
     *
     * @return YukarLinq 入力シーケンスのコピーとなるシーケンス
     */
    public function asEnumerable(): YukarLinq;

    /**
     * シーケンスの要素を、指定した型にキャストします。
     *
     * @param string $type キャストする型の名前
     *
     * @return YukarLinq 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス
     */
    public function cast(string $type): YukarLinq;

    /**
     * 指定された型に基づいてシーケンスの要素をフィルター処理します。
     *
     * @param string $type シーケンスの要素をフィルター処理する型の名前
     *
     * @return YukarLinq 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス。
     *                      キャストに失敗した要素は含まれません。
     */
    public function ofType(string $type): YukarLinq;
}
