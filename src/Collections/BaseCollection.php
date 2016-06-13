<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Interfaces\Collections\ICollection;

/**
 * コレクションを操作する機能を提供する抽象クラスです。
 */
abstract class BaseCollection extends BaseCommonCollection implements ICollection
{
    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     *        <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getSourceList()->getArrayCopy());
    }

    /**
     * コレクションに項目を追加します。
     *
     * @param mixed $item コレクションに追加するオブジェクト
     */
    public function add($item)
    {
        $this->getSourceList()->append($item);
    }

    /**
     * コレクションに特定の値が格納されているかどうかを判断します。
     *
     * @param mixed $item コレクション内で検索するオブジェクト
     *
     * @return bool オブジェクトがコレクションに存在する場合は true。それ以外の場合は false。
     */
    public function containsItem($item): bool
    {
        return in_array($item, $this->getSourceList()->getArrayCopy(), true);
    }

    /**
     * コレクションの要素を配列にコピーします。
     *
     * @param array $dst      コレクションから要素がコピーされる一次元配列
     * @param int $copy_count コレクションから配列へコピーする要素の数
     * @param int $copy_start コレクションのコピー対象となる開始の位置
     */
    public function copyTo(array &$dst, int $copy_count = null, int $copy_start = 0)
    {
        $dst = array_slice($this->getSourceList()->getArrayCopy(), $copy_start, $copy_count);
    }

    /**
     * コレクション内で最初に見つかった特定のオブジェクトを削除します。
     *
     * @param mixed $value コレクションから削除するオブジェクト
     *
     * @return bool コレクションから $value が正常に削除された場合は true。それ以外の場合は false。
     */
    public function remove($value): bool
    {
        try {
            $source_list = $this->getSourceList();
            $source_list->offsetUnset($this->getIndexOf($value));
            $source_list->exchangeArray(array_values($source_list->getArrayCopy()));
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected function getIndexOf($value, int $index = 0, int $length = null, bool $is_reversed = false): int
    {
        $find_index = array_search(
            $value,
            $this->reversal($this->slice($index, $length), $is_reversed),
            true
        ) ?? -1;

        return is_int($find_index) ? $find_index : -1;
    }
}
