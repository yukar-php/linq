<?php
namespace Yukar\Linq\Interfaces\Collections;

/**
 * コレクションを操作するメソッドを定義します。
 */
interface ICollection extends \IteratorAggregate
{
    /**
     * コレクションに項目を追加します。
     *
     * @param mixed $item コレクションに追加するオブジェクト
     */
    public function add($item);

    /**
     * コレクションからすべての項目を削除します。
     */
    public function clear();

    /**
     * コレクションに特定の値が格納されているかどうかを判断します。
     *
     * @param mixed $item コレクション内で検索するオブジェクト
     *
     * @return bool オブジェクトがコレクションに存在する場合は true。それ以外の場合は false。
     */
    public function containsItem($item): bool;

    /**
     * コレクションの要素を配列にコピーします。
     *
     * @param array $dst コレクションから要素がコピーされる一次元配列
     * @param int $copy_count コレクションから配列へコピーする要素の数
     * @param int $copy_start コレクションのコピー対象となる開始の位置
     */
    public function copyTo(array &$dst, int $copy_count = -1, int $copy_start = 0);

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

    /**
     * コレクション内で最初に見つかった特定のオブジェクトを削除します。
     *
     * @param mixed $value コレクションから削除するオブジェクト
     *
     * @return bool コレクションから $value が正常に削除された場合は true。それ以外の場合は false。
     */
    public function remove($value): bool;
}
