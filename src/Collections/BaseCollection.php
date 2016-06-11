<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Interfaces\Collections\ICollection;

/**
 * コレクションを操作する機能を提供する抽象クラスです。
 */
abstract class BaseCollection extends BaseEnumerable implements ICollection
{
    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *        <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getSourceList());
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
     * コレクションからすべての項目を削除します。
     */
    public function clear()
    {
        $this->getSourceList()->exchangeArray([]);
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
     * コレクションに格納されている要素の数を取得します。
     *
     * @return number コレクションに格納されている要素の数
     */
    public function getSize()
    {
        return $this->getSourceList()->count();
    }

    /**
     * コレクションが読み取り専用であるかどうかを示す値を取得します。
     *
     * @return bool コレクションが読み取り専用である場合は true。それ以外の場合は false。
     */
    public function isReadOnly(): bool
    {
        return false;
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

    protected function splice(int $index = 0, int $count = null, array $replace_list = [])
    {
        $target_list = $this->getSourceList()->getArrayCopy();

        array_splice($target_list, $index, $count, $replace_list);

        return $target_list;
    }

    protected function slice(int $index = 0, int $count = null, bool $preserve_keys = true): array
    {
        return array_slice($this->getSourceList()->getArrayCopy(), $index, $count, $preserve_keys);
    }

    protected function reversal($list, bool $is_reversed = true, bool $preserve_keys = true): array
    {
        return ($is_reversed === true) ? array_reverse($list, $preserve_keys) : $list;
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
