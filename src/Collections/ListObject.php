<?php
namespace Yukar\Linq\Collections;

/**
 * インデックスを使用してアクセスできるオブジェクトのリストを表します。
 */
class ListObject extends BaseList
{
    /**
     * ListObject クラスの新しいインスタンスを初期化します。
     *
     * @param array $input 初期化時に保持する要素のリスト
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input);
    }

    /**
     * 指定したコレクションの要素をコレクションの末尾に追加します。
     *
     * @param array|\Traversable $collection コレクションの末尾に要素が追加されるコレクション
     *
     * @throws \TypeError コレクションが配列またはトラバース可能なオブジェクトではありません。
     */
    public function addRange($collection)
    {
        if ($this->isTraversable($collection) === false) {
            throw new \TypeError();
        }

        foreach ($collection as $value) {
            $this->add($value);
        }
    }

    /**
     * 指定された条件に一致する要素がコレクションに含まれているかどうかを判断します。
     *
     * @param \Closure $match 検索する要素の条件を定義するクロージャ
     *
     * @return bool 条件に一致する要素がコレクションに少なくとも1つ含まれている場合は true。それ以外の場合は false。
     */
    public function exists(\Closure $match): bool
    {
        return is_array($this->getItemAtRange($this->slice(), $match));
    }

    /**
     * コレクションから指定された条件に一致する要素のうち最初に見つかった要素を返します。
     *
     * @param \Closure $match 検索する要素の条件を定義するクロージャ
     *
     * @return mixed 見つかった場合は、指定された条件と一致する最初の要素。
     */
    public function find(\Closure $match)
    {
        return $this->getItemAtRange($this->slice(), $match)['value'] ?? null;
    }

    /**
     * 指定された条件と一致するすべての要素を取得します。
     *
     * @param \Closure $match 検索する要素の条件を定義するクロージャ
     *
     * @return ListObject 指定した条件に一致する要素が見つかった場合は、そのすべての要素を格納するコレクション。
     * それ以外の場合は、空の List<T>。
     */
    public function findAll(\Closure $match): ListObject
    {
        return new ListObject($this->getMatchedArray($match, true));
    }

    /**
     * コレクションの指定したインデックスから指定した要素数までの範囲内で、指定した条件に一致する要素を検索し、
     * その要素の中で最も小さいインデックス番号を返します。
     *
     * @param \Closure $match  検索する要素の条件を定義するクロージャ
     * @param int $start_index 検索の開始位置を示す0から始まるインデックス
     * @param int $count       検索対象の範囲内にある要素の数
     *
     * @return int 引数 $match の条件と一致する要素が存在した場合、その要素の中で最も小さいインデックス番号。
     * それ以外の場合は –1。
     */
    public function findIndex(\Closure $match, int $start_index = 0, int $count = null): int
    {
        $length = $this->calcLength($start_index, $count);

        if ($this->isOutOfRangeIndex($start_index, $length) === true) {
            throw new \OutOfRangeException();
        }

        return $this->getItemAtRange($this->slice($start_index, $length), $match)['key'] ?? -1;
    }

    /**
     * コレクションから指定された条件に一致する要素のうち最後に見つかった要素を返します。
     *
     * @param \Closure $match 検索する要素の条件を定義するクロージャ
     *
     * @return mixed 見つかった場合は、指定された条件と一致する最後の要素。
     */
    public function findLast(\Closure $match)
    {
        return $this->getItemAtRange($this->reversal($this->slice()), $match)['value'] ?? null;
    }

    /**
     * コレクションの指定したインデックスから指定した要素数までの範囲内で、指定した条件に一致する要素を検索し、
     * その要素の中で最も大きいインデックス番号を返します。
     *
     * @param \Closure $match  検索する要素の条件を定義するクロージャ
     * @param int $start_index 検索の開始位置を示す0から始まるインデックス
     * @param int $count       検索対象の範囲内にある要素の数
     *
     * @return int 引数 $match の条件と一致する要素が存在した場合、その要素の中で最も大きいインデックス番号。
     * それ以外の場合は –1。
     */
    public function findLastIndex(\Closure $match, int $start_index = 0, int $count = null): int
    {
        $length = $this->calcLength($start_index, $count);

        if ($this->isOutOfRangeIndex($start_index, $length) === true) {
            throw new \OutOfRangeException();
        }

        return $this->getItemAtRange($this->reversal($this->slice($start_index, $length)), $match)['key'] ?? -1;
    }

    /**
     * コレクションの各要素に対して、指定された処理を実行します。
     *
     * @param \Closure $action コレクションの各要素に対して実行するクロージャ
     */
    public function walk(\Closure $action)
    {
        foreach ($this as $value) {
            $action->call($this, $value);
        }
    }

    /**
     * コピー元のコレクション内の指定した範囲の要素のコピーを作成します。
     *
     * @param int $index コピー範囲の開始する位置となるインデックス番号
     * @param int $count コピーする範囲の要素数
     *
     * @return ListObject コピー元のコレクション内の指定した範囲の要素のコピー
     */
    public function getRange(int $index, int $count): ListObject
    {
        if ($this->isOutOfRangeArguments($index, $count) === true) {
            throw new \OutOfRangeException();
        } elseif ($this->isInvalidRange($index, $count) === true) {
            throw new \InvalidArgumentException();
        }

        return new ListObject($this->slice($index, $count, false));
    }

    /**
     * 引数に渡したコレクションの要素をコレクション内の指定したインデックスの位置に挿入します。
     *
     * @param int $index                     新しい要素が挿入される位置のインデックス
     * @param array|\Traversable $collection コレクションへ挿入する要素を持つコレクション
     *
     * @throws \OutOfRangeException 引数 $index が 0 未満です。または、引数 $index がコレクションの要素数より大きくなっています。
     * @throws \TypeError コレクションが配列またはトラバース可能なオブジェクトではありません。
     */
    public function insertRange(int $index, $collection)
    {
        if ($this->isTraversable($collection) === false) {
            throw new \TypeError();
        }

        try {
            $i = 0;

            foreach ($collection as $item) {
                $this->insert($index + $i, $item);
                $i += 1;
            }
        } catch (\OutOfRangeException $e) {
            throw $e;
        }
    }

    /**
     * コレクションの指定したインデックスから指定した要素数までの範囲内で、指定した条件に一致する要素を検索し、
     * 最後に出現する位置の 0 から始まるインデックスを返します。
     *
     * @param mixed $value コレクション内で検索するオブジェクト
     * @param int $index   検索の開始位置を示す0から始まるインデックス
     * @param int $count   検索対象の範囲内にある要素の数
     *
     * @return int 引数 $value のオブジェクトがリストに存在する場合はその位置。それ以外の場合は -1。
     */
    public function lastIndexOf($value, int $index = 0, int $count = null): int
    {
        $length = $this->calcLength($index, $count);

        if ($this->isOutOfRangeIndex($index, $length) === true) {
            throw new \OutOfRangeException();
        }

        return $this->getIndexOf($value, $index, $length, true);
    }

    /**
     * 指定された条件に一致するすべての要素を削除します。
     *
     * @param \Closure $match 削除する要素の条件を定義するクロージャ
     *
     * @return int コレクションから削除された要素の数
     */
    public function removeAll(\Closure $match): int
    {
        $target_list = $this->getSourceList();
        $before_count = $target_list->count();
        $target_list->exchangeArray($this->getMatchedArray($match, false));

        return $before_count - $target_list->count();
    }

    /**
     * コレクションから要素の範囲を削除します。
     *
     * @param int $index 削除する要素の範囲の開始位置を示す0から始まるインデックス
     * @param int $count 削除する要素の数
     */
    public function removeRange(int $index, int $count)
    {
        if ($this->isOutOfRangeArguments($index, $count) === true) {
            throw new \OutOfRangeException();
        } elseif ($this->isInvalidRange($index, $count) === true) {
            throw new \InvalidArgumentException();
        }

        $this->getSourceList()->exchangeArray($this->splice($index, $count));
    }

    /**
     * コレクションの指定した範囲を反転します。引数が未指定の場合はコレクションの全ての要素を反転します。
     *
     * @param int $index 反転する要素の範囲の開始位置を示す0から始まるインデックス
     * @param int $count 反転する要素の数
     */
    public function reverse(int $index = 0, int $count = null)
    {
        $length = $this->calcLength($index, $count);

        if ($this->isOutOfRangeIndex($index, $length) === true) {
            throw new \OutOfRangeException();
        }

        $this->getSourceList()->exchangeArray(
            $this->splice($index, $length, $this->reversal($this->slice($index, $length), true))
        );
    }

    /**
     * コレクションの全ての要素を並べ替えます。
     */
    public function sort()
    {
        $this->getSourceList()->asort();
        $this->getSourceList()->exchangeArray(
            $this->splice(0, $this->calcLength(0), $this->getSourceList()->getArrayCopy())
        );
    }

    /**
     * コレクションの全ての要素が指定した条件に一致するかどうかを調べます。
     *
     * @param \Closure $match 要素の条件を定義するクロージャ
     *
     * @return bool コレクションの全ての要素が指定した条件を満たす場合は true。それ以外の場合は false。
     */
    public function trueForAll(\Closure $match): bool
    {
        foreach ($this->getSourceList() as $key => $value) {
            if ($match->call($this, $value) !== true) {
                return false;
            }
        }

        return true;
    }

    private function getMatchedArray(\Closure $match, bool $is_matched): array
    {
        $matched_list = [];

        foreach ($this as $key => $value) {
            ($match->call($this, $value) === $is_matched) && $matched_list[] = $value;
        }

        return $matched_list;
    }

    private function isTraversable($param): bool
    {
        return (is_array($param) === true || ($param instanceof \Traversable) === true);
    }
}
