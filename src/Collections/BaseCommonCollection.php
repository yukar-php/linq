<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Interfaces\Collections\ICommonCollection;

/**
 * コレクションを操作する共通機能を実装するための抽象クラスです。
 */
abstract class BaseCommonCollection extends BaseEnumerable implements ICommonCollection
{
    /**
     * コレクションからすべての項目を削除します。
     */
    public function clear()
    {
        $this->getSourceList()->exchangeArray([]);
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
}
