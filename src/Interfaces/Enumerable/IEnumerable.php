<?php
namespace Yukar\Linq\Interfaces\Enumerable;

use Yukar\Linq\Collections\DictionaryObject;
use Yukar\Linq\Collections\ListObject;

/**
 * 単純な反復処理をサポートする機能を提供するインターフェイスです。
 */
interface IEnumerable extends ICalculation, IInspection, IExtract, IQuery, ISearch, ISet
{
    /**
     * シーケンスから新しい配列を作成します。
     *
     * @return array シーケンスから作成した新しい配列
     */
    public function toArray(): array;

    /**
     * シーケンスから新しいリストを作成します。
     *
     * @return ListObject シーケンスから作成した新しいリスト
     */
    public function toList(): ListObject;

    /**
     * シーケンスから新しい連想リストを作成します。
     *
     * @param \Closure $key_selector   各要素からキーを抽出する関数
     * @param \Closure $value_selector 各要素から値を抽出する関数
     *
     * @return DictionaryObject シーケンスから作成した新しい連想リスト
     */
    public function toDictionary(\Closure $key_selector, \Closure $value_selector = null): DictionaryObject;
}
