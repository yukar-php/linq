<?php
namespace Yukar\Linq\Collections;

/**
 * オブジェクトをハッシュテーブルとして動作させます。
 */
class DictionaryObject extends \ArrayObject
{
    private $key_list = [];

    /**
     * DictionaryObject クラスの新しいインスタンスを初期化します。
     *
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct(int $flags = 0, string $iterator_class = 'ArrayIterator')
    {
        parent::__construct([], $flags, $iterator_class);
    }

    /**
     * シーケンスに値を追加します。
     *
     * @param KeyValuePair $value キーと値のペア
     */
    public function append($value)
    {
        if ($value instanceof KeyValuePair === false) {
            throw new \InvalidArgumentException();
        }

        $this->offsetSet($value->getKey(), $value->getValue());
    }

    /**
     * シーケンスに指定したキーで値を追加します。
     *
     * @param mixed $key シーケンスに追加するキー
     * @param mixed $value キーに紐づけられる値
     */
    public function offsetSet($key, $value)
    {
        $this->addKeyList($key);
        parent::offsetSet($this->count(), new KeyValuePair($key, $value));
    }

    /**
     * シーケンスから指定したキーから値を取得します。
     *
     * @param mixed $key 値を取得するキー
     *
     * @return mixed シーケンスのキーから取得した値
     */
    public function offsetGet($key)
    {
        if ($this->offsetExists($key) === false) {
            throw new \OutOfBoundsException();
        }

        return parent::offsetGet($this->getKeyListValue($key));
    }

    /**
     * シーケンスに指定したキーに値が存在するかどうかを判別します。
     *
     * @param mixed $key 値が存在するかどうかを判別するキー
     *
     * @return bool シーケンスに指定した値が存在する場合は true。それ以外の場合は false。
     */
    public function offsetExists($key): bool
    {
        return parent::offsetExists($this->getKeyListValue($key));
    }

    /**
     * シーケンスから指定したキーとその値を削除します。
     *
     * @param mixed $key シーケンスから削除するキー
     */
    public function offsetUnset($key)
    {
        parent::offsetUnset($this->getKeyListValue($key));
        $this->unsetKeyListValue($key);
    }

    private function addKeyList($key)
    {
        $this->key_list[$key] = $this->count();
    }

    private function getKeyListValue($key)
    {
        return array_key_exists($key, $this->key_list) ? $this->key_list[$key] : $key;
    }

    private function unsetKeyListValue($key)
    {
        unset($this->key_list[$key]);
    }
}
