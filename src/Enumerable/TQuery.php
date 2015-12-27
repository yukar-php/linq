<?php
namespace Yukar\Linq\Enumerable;

/**
 * 射影や選択を行う機能を提供します。
 */
trait TQuery
{
    /**
     * 要素のインデックスを組み込むことにより、シーケンスの各要素を新しいフォームに射影します。
     * 
     * @param \ArrayObject $source 変換関数を呼び出す対象となる値のシーケンス
     * @param \Closure $selector 各ソース要素に適用する変換関数。
     *                           この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return \ArrayObject 各要素に対して変換関数を呼び出した結果として得られる要素を含むシーケンス
     */
    public function select(\ArrayObject $source, \Closure $selector): \ArrayObject
    {
        $filtering = new \ArrayObject([]);
        
        foreach ($source->getIterator() as $key => $value) {
            $filtering->append($selector($value, $key));
        }
        
        return $filtering;
    }

    /**
     * シーケンスから一意の要素を返します。
     * 
     * @param \ArrayObject $source 重複する要素を削除する対象となるシーケンス
     *
     * @return \ArrayObject ソースとなるシーケンスの一意の要素を格納するシーケンス
     */
    public function distinct(\ArrayObject $source): \ArrayObject
    {
        return new \ArrayObject(array_unique($source->getArrayCopy(), SORT_REGULAR));
    }
    
    /**
     * 述語に基づいて値のシーケンスをフィルター処理します。
     * 
     * @param \ArrayObject $source フィルター処理するシーケンス
     * @param \Closure $predicate 各ソース要素が条件に当てはまるかどうかをテストする関数。
     *                            この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return \ArrayObject 各ソース要素が条件に当てはまるかどうかをテストする関数。
     * この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     */
    public function where(\ArrayObject $source, \Closure $predicate): \ArrayObject
    {
        $filtering = new \ArrayObject([]);
        
        foreach ($source->getIterator() as $key => $value) {
            ($predicate($value, $key) === true) && $filtering->append($value);
        }
        
        return $filtering;
    }
}
