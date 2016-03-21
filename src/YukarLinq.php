<?php
namespace Yukar\Linq;

use Yukar\Linq\Enumerable\TCalculation;
use Yukar\Linq\Enumerable\TExtract;
use Yukar\Linq\Enumerable\TInspection;
use Yukar\Linq\Enumerable\TSet;
use Yukar\Linq\Interfaces\ILinqFunc;

/**
 * シーケンスに対してLINQ機能を提供します。
 */
class YukarLinq extends BaseListProperty implements ILinqFunc
{
    use TCalculation, TInspection, TExtract, TSet;
    
    /**
     * YukarLinq クラスの新しいインスタンスを初期化します。
     *
     * @param \ArrayObject $list 新しいインスタンスが所持する操作対象となる配列オブジェクトのインスタンス
     */
    public function __construct(\ArrayObject $list)
    {
        $this->setSourceList($list);
    }

    /**
     * シーケンスのコピーを返します。
     *
     * @return YukarLinq 入力シーケンスのコピーとなるシーケンス
     */
    public function asEnumerable(): YukarLinq
    {
        return $this->setSourceList($this->asEnumerableOf($this->getSourceList()));
    }

    /**
     * シーケンスの要素を、指定した型にキャストします。
     *
     * @param string $type キャストする型の名前
     *
     * @return YukarLinq 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス
     */
    public function cast(string $type): YukarLinq
    {
        return $this->setSourceList($this->castOf($this->getSourceList(), $type));
    }

    /**
     * 指定された型に基づいてシーケンスの要素をフィルター処理します。
     *
     * @param string $type シーケンスの要素をフィルター処理する型の名前
     *
     * @return YukarLinq 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス。
     *                      キャストに失敗した要素は含まれません。
     */
    public function ofType(string $type): YukarLinq
    {
        return $this->setSourceList($this->ofTypeOf($this->getSourceList(), $type));
    }
    
    /**
     * 値のシーケンスの合計を計算します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return number シーケンスの値の合計
     */
    public function sum(\Closure $selector = null)
    {
        return $this->sumOf($this->getSourceList(), $selector);
    }

    /**
     * 値のシーケンスの平均値を計算します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return float 値のシーケンスの平均値
     */
    public function average(\Closure $selector = null)
    {
        return $this->averageOf($this->getSourceList(), $selector);
    }

    /**
     * シーケンスの各要素に対して変換関数を呼び出し、最大値を返します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return mixed シーケンスの最大値
     */
    public function max(\Closure $selector = null)
    {
        return $this->maxOf($this->getSourceList(), $selector);
    }

    /**
     * シーケンスの各要素に対して変換関数を呼び出し、最小値を返します。
     *
     * @param \Closure|null $selector 各要素に適用する変換関数
     *
     * @return mixed シーケンスの最小値
     */
    public function min(\Closure $selector = null)
    {
        return $this->minOf($this->getSourceList(), $selector);
    }

    /**
     * 条件を満たす指定されたシーケンス内の要素の数を表す数値を返します。
     *
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return int 述語関数の条件を満たす、シーケンス内の要素数を表す数値
     */
    public function count(\Closure $predicate = null): int
    {
        return $this->countOf($this->getSourceList(), $predicate);
    }

    /**
     * シーケンスにアキュムレータ関数を適用します。
     *
     * @param \Closure $func 各要素に対して呼び出すアキュムレータ関数
     *
     * @return mixed 最終的なアキュムレータ値
     */
    public function aggregate(\Closure $func)
    {
        return $this->aggregateOf($this->getSourceList(), $func);
    }
    
    /**
     * シーケンス内の指定された数の要素をバイパスし、残りの要素を返します。
     *
     * @param int $count 残りの要素を返す前にスキップする要素の数
     *
     * @return YukarLinq 入力シーケンスで指定されたインデックスの後に出現する要素を含むシーケンス
     */
    public function skip(int $count): YukarLinq
    {
        return $this->setSourceList($this->skipOf($this->getSourceList(), $count));
    }

    /**
     * 指定された条件が満たされる限り、シーケンスの要素をバイパスした後、残りの要素を返します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return YukarLinq テストに初めて合格しない要素から入力シーケンスの最後の要素までのシーケンス
     */
    public function skipWhile(\Closure $predicate): YukarLinq
    {
        return $this->setSourceList($this->skipWhileOf($this->getSourceList(), $predicate));
    }

    /**
     * シーケンスの先頭から、指定された数の連続する要素を返します。
     *
     * @param int $count 返す要素数
     *
     * @return YukarLinq 入力シーケンスの先頭から、指定された数の要素を含むシーケンス
     */
    public function take(int $count): YukarLinq
    {
        return $this->setSourceList($this->takeOf($this->getSourceList(), $count));
    }

    /**
     * 指定された条件が満たされる限り、シーケンスから要素を返します
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return YukarLinq 入力シーケンスの先頭の要素からテストに初めて合格しない要素の前に出現する要素までを含むシーケンス
     */
    public function takeWhile(\Closure $predicate): YukarLinq
    {
        return $this->setSourceList($this->takeWhileOf($this->getSourceList(), $predicate));
    }

    /**
     * 要素のインデックスを組み込むことにより、シーケンスの各要素を新しいフォームに射影します。
     *
     * @param \Closure $selector 各ソース要素に適用する変換関数。
     *                           この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return YukarLinq 各要素に対して変換関数を呼び出した結果として得られる要素を含むシーケンス
     */
    public function select(\Closure $selector): YukarLinq
    {
        return $this->setSourceList($this->selectOf($this->getSourceList(), $selector));
    }

    /**
     * シーケンスから一意の要素を返します。
     *
     * @return YukarLinq ソースとなるシーケンスの一意の要素を格納するシーケンス
     */
    public function distinct(): YukarLinq
    {
        return $this->setSourceList($this->distinctOf($this->getSourceList()));
    }

    /**
     * 述語に基づいて値のシーケンスをフィルター処理します。
     *
     * @param \Closure $predicate 各ソース要素が条件に当てはまるかどうかをテストする関数。
     *                            この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return YukarLinq 各ソース要素が条件に当てはまるかどうかをテストする関数。
     * この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     */
    public function where(\Closure $predicate): YukarLinq
    {
        return $this->setSourceList($this->whereOf($this->getSourceList(), $predicate));
    }

    /**
     * シーケンスのすべての要素が条件を満たしているかどうかを判断します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return bool シーケンスのすべての要素がテストに合格する場合は true。それ以外の場合は false。
     */
    public function all(\Closure $predicate): bool
    {
        return $this->allOf($this->getSourceList(), $predicate);
    }

    /**
     * シーケンスの任意の要素が条件を満たしているかどうかを判断します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return bool シーケンスの要素がテストに合格する場合は true。それ以外の場合は false。
     */
    public function any(\Closure $predicate): bool
    {
        return $this->anyOf($this->getSourceList(), $predicate);
    }

    /**
     * 指定した要素がシーケンスに含まれているかどうかを判断します。
     *
     * @param mixed $value シーケンス内で検索する値
     *
     * @return bool 指定した値を持つ要素がシーケンスに含まれている場合は true。それ以外は false。
     */
    public function contains($value): bool
    {
        return $this->containsOf($this->getSourceList(), $value);
    }

    /**
     * シーケンス内の指定されたインデックス位置にある要素を返します。
     *
     * @param int $index 取得する要素の 0 から始まるインデックス
     *
     * @return mixed シーケンス内の指定された位置にある要素
     */
    public function elementAt(int $index)
    {
        return $this->elementAtOf($this->getSourceList(), $index);
    }

    /**
     * 指定された条件を満たすシーケンスの最初の要素を返します。
     *
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 指定された述語関数でテストに合格するシーケンスの最初の要素
     */
    public function first(\Closure $predicate = null)
    {
        return $this->firstOf($this->getSourceList(), $predicate);
    }

    /**
     * 指定された条件を満たすシーケンスの最後の要素を返します。
     *
     * @param \Closure|null $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 指定された述語関数でテストに合格するシーケンスの最後の要素
     */
    public function last(\Closure $predicate = null)
    {
        return $this->lastOf($this->getSourceList(), $predicate);
    }

    /**
     * 条件を満たす、シーケンスの唯一の要素を返します。そのような要素が複数存在する場合は、例外をスローします。
     *
     * @param \Closure $predicate 要素が条件を満たしているかどうかをテストする関数
     *
     * @return mixed 条件を満たす入力シーケンスの 1 つの要素
     */
    public function single(\Closure $predicate)
    {
        return $this->singleOf($this->getSourceList(), $predicate);
    }

    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの差集合を生成します。
     *
     * @param YukarLinq $second 最初のシーケンスにも含まれ、返されたシーケンスからは削除される要素を含むシーケンス
     *
     * @return YukarLinq 2 つのシーケンスの要素の差集合が格納されているシーケンス
     */
    public function except(YukarLinq $second): YukarLinq
    {
        return $this->setSourceList($this->exceptOf($this->getSourceList(), $second->getSourceList()));
    }

    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの積集合を生成します。
     *
     * @param YukarLinq $second 最初のシーケンスにも含まれる、返される一意の要素を含むシーケンス
     *
     * @return YukarLinq 2 つのシーケンスの積集合を構成する要素が格納されているシーケンス
     */
    public function intersect(YukarLinq $second): YukarLinq
    {
        return $this->setSourceList($this->intersectOf($this->getSourceList(), $second->getSourceList()));
    }

    /**
     * 既定の等値比較子を使用して、2 つのシーケンスの和集合を生成します。
     *
     * @param YukarLinq $second 和集合の 2 番目のセットを形成する一意の要素を含むシーケンス
     *
     * @return YukarLinq 2 つの入力シーケンスの要素 (重複する要素は除く) を格納しているシーケンス
     */
    public function union(YukarLinq $second): YukarLinq
    {
        return $this->setSourceList($this->unionOf($this->getSourceList(), $second->getSourceList()));
    }

    /**
     * 2 つのシーケンスを連結します。
     *
     * @param YukarLinq $second 最初のシーケンスに連結するシーケンス
     *
     * @return YukarLinq 2 つの入力シーケンスの連結された要素が格納されているシーケンス
     */
    public function concat(YukarLinq $second): YukarLinq
    {
        return $this->setSourceList($this->concatOf($this->getSourceList(), $second->getSourceList()));
    }

    /**
     * 2 つのシーケンスの対応する要素に対して、1 つの指定した関数を適用し、結果として 1 つのシーケンスを生成します。
     *
     * @param YukarLinq $second マージする 2 番目のシーケンス
     * @param \Closure $resultSelector 2 つのシーケンスの要素をマージする方法を指定する関数
     *
     * @return YukarLinq 2 つの入力シーケンスのマージされた要素が格納されているシーケンス
     */
    public function zip(YukarLinq $second, \Closure $resultSelector): YukarLinq
    {
        return $this->setSourceList($this->zipOf($this->getSourceList(), $second->getSourceList(), $resultSelector));
    }

    /**
     * 要素の型に対して既定の等値比較子を使用して要素を比較することで、2 つのシーケンスが等しいかどうかを判断します。
     *
     * @param YukarLinq $second 最初のシーケンスと比較するシーケンス
     *
     * @return bool 2 つのソースシーケンスが同じ長さで、それらに対応する要素が等しい場合は true。
     * それ以外の場合は false。
     */
    public function sequenceEqual(YukarLinq $second): bool
    {
        return $this->sequenceEqualOf($this->getSourceList(), $second->getSourceList());
    }
}
