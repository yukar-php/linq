<?php
namespace Yukar\Linq\Interfaces\Collections;

/**
 * キーと値のペアのコレクションを操作するメソッドを定義します。
 */
interface IDictionary extends ICommonCollection, \ArrayAccess
{
    /**
     * 指定したキーと値のペアの要素をコレクションに追加します。
     *
     * @param mixed $key   追加する要素のキー
     * @param mixed $value 追加する要素の値
     */
    public function add($key, $value);

    /**
     * 指定したキーの要素がコレクションに存在するかどうかを調べます。
     *
     * @param mixed $key コレクション内で検索されるキー
     *
     * @return bool 指定したキーの要素がコレクションに存在する場合は true。それ以外の場合は false。
     */
    public function containsKey($key): bool;

    /**
     * コレクションが保持しているキーの一覧を取得します。
     *
     * @return array コレクションが保持しているキーの一覧
     */
    public function getKeys(): array;

    /**
     * コレクションの保持している値の一覧を取得します。
     *
     * @return array コレクションの保持している値の一覧
     */
    public function getValues(): array;

    /**
     * 指定したキーを持つ要素をコレクションから削除します。
     *
     * @param mixed $key コレクションから削除する要素のキー
     *
     * @return bool 要素がコレクションから正常に削除された場合は true。それ以外の場合は false。
     */
    public function remove($key): bool;

    /**
     * 指定したキーに関連付けされている値を取得します。
     *
     * @param mixed $key   値を取得するキー
     * @param mixed $value キーが存在する場合はその値を、それ以外の時はNULLを代入する
     *
     * @return bool 指定したキーを持つ要素がコレクションに存在する場合は true。それ以外の場合は false。
     */
    public function tryGetValue($key, &$value): bool;
}
