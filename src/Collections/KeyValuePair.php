<?php
namespace Yukar\Linq\Collections;

/**
 * 設定または取得できる、キー/値のペアを定義します。
 */
class KeyValuePair
{
    private $pair_key = null;
    private $pair_value = null;

    /**
     * KeyValuePair クラスの新しいインスタンスを初期化します。
     *
     * @param mixed $key キー
     * @param mixed $value 値
     */
    public function __construct($key, $value)
    {
        $this->setPairKey($key);
        $this->setPairValue($value);
    }

    /**
     * キーを取得します。
     *
     * @return mixed キー
     */
    public function getKey()
    {
        return $this->pair_key;
    }

    /**
     * 値を取得します。
     *
     * @return mixed 値
     */
    public function getValue()
    {
        return $this->pair_value;
    }

    /**
     * オブジェクトを文字列として取得します。
     *
     * @return string 値
     */
    public function __toString(): string
    {
        return strval($this->getValue() ?? '');
    }

    private function setPairKey($val)
    {
        ($this->pair_key == null) && $this->pair_key = $val;
    }

    private function setPairValue($val)
    {
        ($this->pair_value == null) && $this->pair_value = $val;
    }
}
