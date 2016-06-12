<?php
namespace Yukar\Linq\Collections;

/**
 * キーと値のペアのコレクションの要素を列挙できるイテレータークラスです。
 */
class DictionaryIterator extends \ArrayIterator
{
    /**
     * DictionaryIterator クラスの新しいインスタンスを初期化します。
     *
     * @param array $array イテレーターの対象となる配列
     * @param int $flags   イテレーターの振る舞いを制御するフラグ
     */
    public function __construct(array $array, $flags = 0)
    {
        parent::__construct($this->convertHashArray($array), $flags);
    }

    private function convertHashArray(array $list): array
    {
        $converted_list = [];
        $is_key_value_pair = (count($list) > 0 && reset($list) instanceof KeyValuePair);

        foreach ($list as $key => $value) {
            $convert_key = $is_key_value_pair ? $value->getKey() : $key;
            $convert_value = $is_key_value_pair ? $value->getValue() : $value;

            $converted_list[$convert_key] = $convert_value;
        }

        return $converted_list;
    }
}
