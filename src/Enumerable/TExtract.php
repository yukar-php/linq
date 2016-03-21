<?php
namespace Yukar\Linq\Enumerable;

/**
 * シーケンスの要素の抽出に関する処理を提供します。
 */
trait TExtract
{
    /**
     * シーケンス内の指定された数の要素をバイパスし、残りの要素を返します。
     *
     * @param \ArrayObject $source 返される要素が含まれるシーケンス
     * @param int $count 残りの要素を返す前にスキップする要素の数
     *
     * @return \ArrayObject 入力シーケンスで指定されたインデックスの後に出現する要素を含むシーケンス
     */
    public function skipOf(\ArrayObject $source, int $count): \ArrayObject
    {
        return $this->getSlicedArrayObject(
            $source,
            ($count <= 0) ? 0 : (($source->count() <= $count) ? $source->count() : $count)
        );
    }

    /**
     * 指定された条件が満たされる限り、シーケンスの要素をバイパスした後、残りの要素を返します。
     *
     * @param \ArrayObject $source 返される要素が含まれるシーケンス
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return \ArrayObject テストに初めて合格しない要素から入力シーケンスの最後の要素までのシーケンス
     */
    public function skipWhileOf(\ArrayObject $source, \Closure $predicate): \ArrayObject
    {
        $skipped = new \ArrayObject($source->getArrayCopy());

        foreach ($source->getIterator() as $key => $value) {
            if ($predicate($value) === false) {
                break;
            }

            $skipped->offsetUnset($key);
        }

        return new \ArrayObject(array_values($skipped->getArrayCopy()));
    }

    /**
     * シーケンスの先頭から、指定された数の連続する要素を返します。
     *
     * @param \ArrayObject $source 要素を返すシーケンス
     * @param int $count 返す要素数
     *
     * @return \ArrayObject 入力シーケンスの先頭から、指定された数の要素を含むシーケンス
     */
    public function takeOf(\ArrayObject $source, int $count): \ArrayObject
    {
        return $this->getSlicedArrayObject(
            $source,
            0,
            ($count <= 0) ? 0 : (($source->count() <= $count) ? $source->count() : $count)
        );
    }

    /**
     * 指定された条件が満たされる限り、シーケンスから要素を返します
     *
     * @param \ArrayObject $source 要素を返すシーケンス
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return \ArrayObject 入力シーケンスの先頭の要素からテストに初めて合格しない要素の前に出現する要素までを含むシーケンス
     */
    public function takeWhileOf(\ArrayObject $source, \Closure $predicate): \ArrayObject
    {
        $taken = new \ArrayObject([]);

        foreach ($source->getIterator() as $key => $value) {
            if ($predicate($value) === false) {
                break;
            }

            $taken->append($value);
        }

        return $taken;
    }

    private function getSlicedArrayObject(\ArrayObject $source, $offset, $length = null): \ArrayObject
    {
        return new \ArrayObject(array_slice($source->getArrayCopy(), $offset, $length));
    }
}
