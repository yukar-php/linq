<?php
namespace Yukar\Linq\Utilities;

/**
 * 配列を操作するための関数を定義します。
 */
final class ArrayControl
{
    /**
     * 引数の配列から指定したキーの要素を取得します。要素がない場合は $default の値を返します。
     *
     * @param array $list 要素を取得する配列
     * @param mixed $key 配列から検索するキー
     * @param mixed $default 要素がない場合に返却されるデフォルト値
     *
     * @return mixed 引数の配列から指定したキーの要素
     */
    public static function getValue(array $list, $key, $default = null)
    {
        return ArrayInspect::isExistValue($list, $key) ? $list[$key] : $default;
    }

    /**
     * 引数の配列から指定したキーを検索して値を取得します。要素がない場合は $default の値を返します。
     *
     * @param array $list 要素を取得する配列
     * @param mixed $key 配列から検索するキーまたは多次元配列から検索するデリミタで区切られた複数のキー
     * @param mixed $default 要素がない場合に返却されるデフォルト値
     * @param string $delimiter 多次元配列にアクセスする時に使用するデリミタ
     *
     * @return mixed 引数の配列から指定したキーの要素
     */
    public static function findValue(array $list, $key, $default = null, string $delimiter = '=>')
    {
        $temp   = $list;
        $result = $default;
        $keys   = explode($delimiter, $key);

        foreach ($keys as $key_value) {
            $temp_key = trim(mb_convert_kana($key_value, 's', 'utf-8'));

            if (ArrayInspect::isExistValue($temp, $temp_key) === false) {
                $result = $default;

                break;
            }

            $result = static::getValue($temp, $temp_key, $default);
            $temp   = $result;
        }

        return $result;
    }

    /**
     * 引数の配列に指定したキーのインデックスに要素を追加します。
     *
     * @param array $list 要素を追加する配列
     * @param mixed $key 配列に追加するキーまたは多次元配列に追加するデリミタで区切られた複数のキー
     * @param mixed $item 配列に追加する要素
     * @param string $delimiter 多次元配列にアクセスする時に使用するデリミタ
     *
     * @return bool 配列に値を追加できた場合は true。それ以外の場合は false。
     */
    public static function putValue(array &$list, $key, $item, $delimiter = '=>'): bool
    {
        $keys     = array_reverse(explode($delimiter, $key));
        $put_list = null;

        static::eachWalk(
            $keys,
            function ($list_key) use (&$put_list, $item) {
                $temp_key = trim(mb_convert_kana($list_key, 's', 'utf-8'));
                $put_list = is_null($put_list) ? [ $temp_key => $item ] : [ $temp_key => $put_list ];
            }
        );

        return static::copyWhen(
            ArrayInspect::isValid($put_list),
            $list,
            function () use ($list, $put_list) {
                return array_replace_recursive($list, $put_list);
            }
        );
    }

    /**
     * 引数の配列の全ての要素にユーザー関数を適用します。
     *
     * @param array $list ユーザー関数を適用させる配列
     * @param \Closure $walker 配列に対して実行するユーザー関数
     * @param mixed $force_result 引数の値で強制して戻り値を返すかどうか
     *
     * @return mixed ユーザー関数を適用した結果。ユーザー関数で戻り値を返していない場合は false。
     *               引数の $force_result を指定している場合は、その値。
     */
    public static function eachWalk(array &$list, \Closure $walker, $force_result = null)
    {
        $result = false;

        foreach ($list as $key => $value) {
            $result = $walker($value, $key, $result);
        }

        return is_bool($force_result) ? $force_result : $result;
    }

    /**
     * 条件を満たす場合に配列のコピーを行います。
     *
     * @param bool $conditions 実行するための条件結果
     * @param array $to コピー先の配列
     * @param mixed $from コピー元の配列またはコピー先に適用するユーザー関数
     *
     * @return bool 配列のコピーを行った場合は true。それ以外の場合は false。
     */
    public static function copyWhen(bool $conditions, array &$to, $from): bool
    {
        $from_list = static::getParsedArray($from);

        if ($conditions !== true || is_null($from_list)) {
            return false;
        }

        $to = $from_list;

        return true;
    }

    /**
     * 条件を満たす場合に配列の統合を行います。
     *
     * @param bool $conditions 実行するための条件結果
     * @param array $target 統合先となる配列
     * @param mixed $merged 統合元となる配列または統合先に適用するユーザー関数
     *
     * @return bool 配列の統合を行った場合は true。それ以外の場合は false。
     */
    public static function mergeWhen(bool $conditions, array &$target, $merged): bool
    {
        $marge_list = static::getParsedArray($merged);

        if ($conditions !== true || is_null($marge_list)) {
            return false;
        }

        return static::eachWalk(
            $marge_list,
            function ($value, $key, $result) use (&$target) {
                return (bool)($result | static::partialMerge($target, $key, $value));
            }
        );
    }

    private static function getParsedArray($value)
    {
        $parsed = static::getParsedValue($value);

        return is_array($parsed) ? $parsed : null;
    }

    private static function partialMerge(array &$list, $key, $value): bool
    {
        if (static::isMergeable($list, $key, $value)) {
            return static::mergeWhen(true, $list[$key], $value);
        } else {
            return static::addWhen(true, $list, $value, $key);
        }
    }

    private static function isMergeable(array $list, $key, $value): bool
    {
        return (isset($list[$key]) && is_array($list[$key]) && is_array($value));
    }

    private static function addWhen(bool $conditions, array &$list, $item, $key = null): bool
    {
        if ($conditions !== true || isset($item) === false) {
            return false;
        }

        $value = static::getParsedValue($item);

        if (ArrayInspect::isValidKey($key)) {
            $list[$key] = $value;
        } else {
            $list[] = $value;
        }

        return true;
    }

    private static function getParsedValue($value)
    {
        return ($value instanceof \Closure) ? $value() : $value;
    }
}
