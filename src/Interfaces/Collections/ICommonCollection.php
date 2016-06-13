<?php
namespace Yukar\Linq\Interfaces\Collections;

/**
 * コレクションを操作する共通のメソッドを定義します。
 */
interface ICommonCollection extends \IteratorAggregate
{
    /**
     * コレクションからすべての項目を削除します。
     */
    public function clear();

    /**
     * コレクションに格納されている要素の数を取得します。
     *
     * @return number コレクションに格納されている要素の数
     */
    public function getSize();

    /**
     * コレクションが読み取り専用であるかどうかを示す値を取得します。
     *
     * @return bool コレクションが読み取り専用である場合は true。それ以外の場合は false。
     */
    public function isReadOnly(): bool;
}
