<?php
namespace Yukar\Linq\Utilities;

/**
 * 配列を検査するための関数を定義します。
 */
final class ArrayInspect
{
    /**
     * 引数の変数が配列型で一つも要素を持たないことを調べます。
     *
     * @param mixed $value 判定を行う変数
     *
     * @return bool 引数の変数が、配列型で一つも要素を持たない場合は true。それ以外の場合は false。
     */
    public static function isEmpty(&$value): bool
    {
        return (is_array($value) && count($value) === 0);
    }

    /**
     * 引数の変数が配列型で一つ以上の要素を持つことを調べます。
     *
     * @param mixed $value 判定を行う変数
     *
     * @return bool 引数の変数が、配列型で一つ以上の要素を持つ場合は true。それ以外の場合は false。
     */
    public static function isValid(&$value): bool
    {
        return (is_array($value) && count($value) > 0);
    }

    /**
     * 引数の値が配列のキーとして妥当であることを調べます。
     *
     * @param mixed $value 判定を行う値
     *
     * @return bool 引数の値が、配列のキーとして妥当な場合は true。それ以外の場合は false。
     */
    public static function isValidKey($value): bool
    {
        return (is_int($value) && $value >= 0) || (is_string($value) && strlen($value) > 0);
    }

    /**
     * 引数の配列に指定したキーが含まれていることを調べます。
     *
     * @param array $list 検索対象の配列
     * @param mixed $key 配列に含まれていることを調べるキー
     *
     * @return bool 引数の配列に指定したキーが含まれている場合は true。それ以外の場合は false。
     */
    public static function isContainsKey(array $list, $key): bool
    {
        return (static::isValidKey($key) && array_key_exists($key, $list));
    }

    /**
     * 引数の配列に指定したキーの要素が存在することを調べます。
     *
     * @param array $list 検索対象の配列
     * @param mixed $key 要素が存在することを調べるキー
     *
     * @return bool 引数の配列に指定したキーの要素が存在する場合は true。それ以外の場合は false。
     */
    public static function isExistValue(array $list, $key): bool
    {
        return (static::isValidKey($key) && isset($list[$key]));
    }
}
