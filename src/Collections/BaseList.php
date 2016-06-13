<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Interfaces\Collections\IList;

/**
 * インデックスによって個別にアクセスできる機能を提供する抽象クラスです。
 */
abstract class BaseList extends BaseCollection implements IList
{
    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->getSourceList()->offsetExists($offset);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->getSourceList()->offsetGet($offset);
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->getSourceList()->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->removeAt($offset);
    }

    /**
     * 指定した項目のコレクション内でのインデックスを調べます。
     *
     * @param mixed $value コレクション内で検索するオブジェクト
     * @param int $index 検索の開始位置を示すインデックス番号
     * @param int $count 検索対象の範囲内にある要素の数
     *
     * @return int 引数 $value のオブジェクトがリストに存在する場合はその位置。それ以外の場合は -1。
     */
    public function indexOf($value, int $index = 0, int $count = null): int
    {
        $length = $this->calcLength($index, $count);

        if ($this->isOutOfRangeIndex($index, $length) === true) {
            throw new \OutOfRangeException();
        }

        return $this->getIndexOf($value, $index, $length);
    }

    /**
     * 指定したインデックスのコレクションに項目を挿入します。
     *
     * @param int $index   引数 $value の挿入位置を0から始まるインデックス
     * @param mixed $value コレクションに挿入するオブジェクト
     */
    public function insert(int $index, $value)
    {
        $src_list = $this->getSourceList();

        if ($index < 0 || $index > $src_list->count()) {
            throw new \OutOfRangeException();
        }

        $this->setSourceList(
            array_merge(
                array_slice($src_list->getArrayCopy(), 0, $index),
                [ $value ],
                array_slice($src_list->getArrayCopy(), $index)
            ),
            $src_list->getFlags(),
            $src_list->getIteratorClass()
        );
    }

    /**
     * 指定したインデックスにあるコレクション項目を削除します。
     *
     * @param int $index 削除する項目の0から始まるインデックス
     */
    public function removeAt(int $index)
    {
        $source_list = $this->getSourceList();

        if ($index < 0 || $index >= $source_list->count()) {
            throw new \OutOfRangeException();
        }

        $source_list->offsetUnset($index);
        $source_list->exchangeArray(array_values($source_list->getArrayCopy()));
    }

    protected function calcLength(int $index, int $count = null): int
    {
        return $count ?? ($this->getSourceList()->count() - $index);
    }

    protected function isOutOfRangeArguments(int $index, int $count): bool
    {
        return ($index < 0 || $count < 0);
    }

    protected function isOutOfRangeIndex(int $index, int $length): bool
    {
        return ($length <= 0 || $index < 0 || $index + $length > $this->getSourceList()->count());
    }

    protected function isInvalidRange(int $index, int $count): bool
    {
        return ($count === 0 || $index + $count > $this->getSourceList()->count());
    }

    protected function getItemAtRange($range, \Closure $closure)
    {
        foreach ($range as $key => $value) {
            if ($closure->call($this, $value) === true) {
                return [ 'key' => $key, 'value' => $value ];
            }
        }

        return null;
    }
}
