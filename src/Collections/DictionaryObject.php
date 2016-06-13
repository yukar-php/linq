<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Interfaces\Collections\IDictionary;

/**
 * キーと値のペアを持つコレクションを表します。
 */
class DictionaryObject extends BaseCommonCollection implements IDictionary
{
    private $key_list = [];
    private $key_type = null;
    private $value_type = null;

    /**
     * BaseDictionary クラスの新しいインスタンスを初期化します。
     *
     * @param array $input 初期化時に保持する要素のリスト
     */
    public function __construct(array $input = [])
    {
        parent::__construct([]);
        (empty($input) === false) && $this->addRange($input);
    }

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
        return new DictionaryIterator($this->getSourceList()->getArrayCopy());
    }

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
        return $this->containsKey($offset);
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
        return $this->getSourceList()->offsetGet($this->getKeyOffset($offset))->getValue();
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
        $this->add($offset, $value);
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
        $this->remove($offset);
    }

    /**
     * 指定したキーと値のペアの要素をコレクションに追加します。
     *
     * @param mixed $key   追加する要素のキー
     * @param mixed $value 追加する要素の値
     */
    public function add($key, $value)
    {
        if ($this->setAllowTypes($key, $value) === false) {
            throw new \InvalidArgumentException();
        }

        $this->getSourceList()->offsetSet($this->setKey($key), new KeyValuePair($key, $value));
    }

    /**
     * 指定したキーの要素がコレクションに存在するかどうかを調べます。
     *
     * @param mixed $key コレクション内で検索されるキー
     *
     * @return bool 指定したキーの要素がコレクションに存在する場合は true。それ以外の場合は false。
     */
    public function containsKey($key): bool
    {
        return ($this->getKeyOffset($key) > -1);
    }

    /**
     * コレクションに特定の値が格納されているかどうかを調べます。
     *
     * @param mixed $value コレクションから探す値
     *
     * @return bool コレクションに指定した値が存在する場合は true。それ以外の場合は false。
     */
    public function containsValue($value): bool
    {
        return in_array($value, $this->getValues(), true);
    }

    /**
     * コレクションが保持しているキーの一覧を取得します。
     *
     * @return array コレクションが保持しているキーの一覧
     */
    public function getKeys(): array
    {
        return array_keys($this->key_list);
    }

    /**
     * コレクションの保持している値の一覧を取得します。
     *
     * @return array コレクションの保持している値の一覧
     */
    public function getValues(): array
    {
        $values = [];

        /** @var KeyValuePair $value */
        foreach ($this->getSourceList()->getArrayCopy() as $value) {
            $values[] = $value->getValue();
        }

        return $values;
    }

    /**
     * 指定したキーを持つ要素をコレクションから削除します。
     *
     * @param mixed $key コレクションから削除する要素のキー
     *
     * @return bool 要素がコレクションから正常に削除された場合は true。それ以外の場合は false。
     */
    public function remove($key): bool
    {
        if ($this->containsKey($key) === true) {
            $this->getSourceList()->offsetUnset($this->setKey($key));
            $this->removeKey($key);
            $this->getSourceList()->exchangeArray(array_values($this->getSourceList()->getArrayCopy()));

            return true;
        }

        return false;
    }

    /**
     * 指定したキーに関連付けされている値を取得します。
     *
     * @param mixed $key   値を取得するキー
     * @param mixed $value キーが存在する場合はその値を、それ以外の時はNULLを代入する
     *
     * @return bool 指定したキーを持つ要素がコレクションに存在する場合は true。それ以外の場合は false。
     */
    public function tryGetValue($key, &$value): bool
    {
        $is_exist_offset = $this->containsKey($key);

        ($is_exist_offset === true) && $value = $this->offsetGet($key);

        return $is_exist_offset;
    }

    protected function setKey($key): int
    {
        if ($this->getKeyOffset($key) < 0) {
            $this->key_list[$key] = $this->getSourceList()->count();
        }

        return $this->getKeyOffset($key);
    }

    protected function getKeyOffset($key): int
    {
        return $this->key_list[$key] ?? -1;
    }

    protected function removeKey($key)
    {
        if ($this->getKeyOffset($key) > -1) {
            unset($this->key_list[$key]);

            if (count($this->key_list) > 0) {
                $this->key_list = array_combine(
                    array_keys($this->key_list),
                    range(0, count($this->key_list) - 1)
                );
            }
        }
    }

    protected function setAllowTypes($key, $value): bool
    {
        $target_key_type = gettype($key);
        $target_value_type = gettype($value);

        if (count($this->key_list) === 0) {
            $this->key_type = $target_key_type;
            $this->value_type = $target_value_type;

            return true;
        }

        return ($this->key_type === $target_key_type && $this->value_type === $target_value_type);
    }

    protected function addRange(array $target_list)
    {
        $keys = array_keys($target_list);
        $values = array_values($target_list);

        for ($i = 0, $length = count($target_list); $i < $length; $i++) {
            $this->add(array_shift($keys), array_shift($values));
        }
    }
}
