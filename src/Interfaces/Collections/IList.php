<?php
namespace Yukar\Linq\Interfaces\Collections;

/**
 * インデックスによって個別にアクセスできるオブジェクトのコレクションを表します。
 */
interface IList extends ICollection, \ArrayAccess
{
    /**
     * 指定した項目のコレクション内でのインデックスを調べます。
     *
     * @param mixed $value コレクション内で検索するオブジェクト
     * @param int $index 検索の開始位置を示すインデックス番号
     * @param int $count 検索対象の範囲内にある要素の数
     *
     * @return int 引数 $value のオブジェクトがリストに存在する場合はその位置。それ以外の場合は -1。
     */
    public function indexOf($value, int $index = 0, int $count = null): int;

    /**
     * 指定したインデックスのコレクションに項目を挿入します。
     *
     * @param int $index 引数 $value の挿入位置を0から始まるインデックス
     * @param mixed $value コレクションに挿入するオブジェクト
     */
    public function insert(int $index, $value);

    /**
     * 指定したインデックスにあるコレクション項目を削除します。
     *
     * @param int $index 削除する項目の0から始まるインデックス
     */
    public function removeAt(int $index);
}
