<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Enumerable\TCalculation;
use Yukar\Linq\Enumerable\TExtract;
use Yukar\Linq\Enumerable\TInspection;
use Yukar\Linq\Enumerable\TSet;
use Yukar\Linq\Interfaces\Enumerable\IEnumerable;

/**
 * 各要素について遅延評価をすることのできるオブジェクトのリストを実装するための抽象クラスです。
 */
abstract class BaseEnumerable implements IEnumerable
{
    use TCalculation
    {
        sumOf as private;
        averageOf as private;
        maxOf as private;
        minOf as private;
        countOf as private;
        aggregateOf as private;
        allOf as private;
        anyOf as private;
        containsOf as private;
        elementAtOf as private;
        firstOf as private;
        lastOf as private;
        singleOf as private;
        selectOf as private;
        distinctOf as private;
        whereOf as private;
    }

    use TInspection
    {
        asEnumerableOf as private;
        castOf as private;
        ofTypeOf as private;
    }

    use TExtract
    {
        skipOf as private;
        skipWhileOf as private;
        takeOf as private;
        takeWhileOf as private;
    }

    use TSet
    {
        exceptOf as private;
        intersectOf as private;
        unionOf as private;
        concatOf as private;
        zipOf as private;
        sequenceEqualOf as private;
    }

    private $list_object = null;
    private $lazy_eval_list = [];

    /**
     * BaseEnumerable クラスの新しいインスタンスを初期化します。
     *
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct($input = [], int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        $this->setSourceList($input, $flags, $iterator_class);
    }

    public function __invoke()
    {
        return $this->evalLazy();
    }

    protected function setSourceList($input, int $flags = 0, string $iterator_class = "ArrayIterator"): self
    {
        $this->list_object = new \ArrayObject($input, $flags, $iterator_class);

        return $this;
    }

    protected function getSourceList(): \ArrayObject
    {
        return $this->list_object;
    }

    protected function addToLazyEval(string $method_name, ...$bind_params): self
    {
        $this->lazy_eval_list[] = function (BaseEnumerable $object) use ($method_name, $bind_params) {
            $reflector = new \ReflectionClass($object);

            $execute_method = $reflector->getMethod("{$method_name}Of");
            $execute_method->setAccessible(true);

            $getter_method = $reflector->getMethod('getSourceList');
            $getter_method->setAccessible(true);

            return $execute_method->invoke($object, $getter_method->invoke($object), ...$bind_params);
        };

        return $this;
    }

    protected function getLazyEvalList(): array
    {
        return $this->lazy_eval_list;
    }

    protected function evalLazy()
    {
        /** @var BaseEnumerable $eval_object */
        $eval_object = (new \ReflectionClass($this))->newInstanceWithoutConstructor();
        $invoker = (new \ReflectionClass($eval_object))->getMethod('setSourceList');
        $invoker->setAccessible(true);
        $invoker->invoke($eval_object, $this->getSourceList());

        foreach ($this->getLazyEvalList() as $key => $lazy_eval_method) {
            $result_value = $lazy_eval_method($eval_object);

            unset($this->lazy_eval_list[$key]);

            if ($result_value instanceof \ArrayObject === false) {
                return $result_value;
            }

            /** @var \ArrayObject $result_value */
            $eval_object->getSourceList()->exchangeArray($result_value->getArrayCopy());
        }

        return $eval_object;
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
        return $this->addToLazyEval(__FUNCTION__, $selector)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $selector)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $selector)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $selector)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $predicate)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $func)->evalLazy();
    }

    /**
     * シーケンス内の指定された数の要素をバイパスし、残りの要素を返します。
     *
     * @param int $count 残りの要素を返す前にスキップする要素の数
     *
     * @return IEnumerable 入力シーケンスで指定されたインデックスの後に出現する要素を含むシーケンス
     */
    public function skip(int $count): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $count);
    }

    /**
     * 指定された条件が満たされる限り、シーケンスの要素をバイパスした後、残りの要素を返します。
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return IEnumerable テストに初めて合格しない要素から入力シーケンスの最後の要素までのシーケンス
     */
    public function skipWhile(\Closure $predicate): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $predicate);
    }

    /**
     * シーケンスの先頭から、指定された数の連続する要素を返します。
     *
     * @param int $count 返す要素数
     *
     * @return IEnumerable 入力シーケンスの先頭から、指定された数の要素を含むシーケンス
     */
    public function take(int $count): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $count);
    }

    /**
     * 指定された条件が満たされる限り、シーケンスから要素を返します
     *
     * @param \Closure $predicate 各要素が条件を満たしているかどうかをテストする関数
     *
     * @return IEnumerable 入力シーケンスの先頭の要素からテストに初めて合格しない要素の前に出現する要素までを含むシーケンス
     */
    public function takeWhile(\Closure $predicate): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $predicate);
    }

    /**
     * シーケンスのコピーを返します。
     *
     * @return IEnumerable 入力シーケンスのコピーとなるシーケンス
     */
    public function asEnumerable(): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__);
    }

    /**
     * シーケンスの要素を、指定した型にキャストします。
     *
     * @param string $type キャストする型の名前
     *
     * @return IEnumerable 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス
     */
    public function cast(string $type): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $type);
    }

    /**
     * 指定された型に基づいてシーケンスの要素をフィルター処理します。
     *
     * @param string $type シーケンスの要素をフィルター処理する型の名前
     *
     * @return IEnumerable 入力シーケンスの各要素を指定された型にキャストした要素を格納するシーケンス。
     *                      キャストに失敗した要素は含まれません。
     */
    public function ofType(string $type): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $type);
    }

    /**
     * 要素のインデックスを組み込むことにより、シーケンスの各要素を新しいフォームに射影します。
     *
     * @param \Closure $selector 各ソース要素に適用する変換関数。
     *                           この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return IEnumerable 各要素に対して変換関数を呼び出した結果として得られる要素を含むシーケンス
     */
    public function select(\Closure $selector): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $selector);
    }

    /**
     * シーケンスから一意の要素を返します。
     *
     * @return IEnumerable ソースとなるシーケンスの一意の要素を格納するシーケンス
     */
    public function distinct(): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__);
    }

    /**
     * 述語に基づいて値のシーケンスをフィルター処理します。
     *
     * @param \Closure $predicate 各ソース要素が条件に当てはまるかどうかをテストする関数。
     *                            この関数の 2 つ目のパラメーターは、ソース要素のインデックスを表します。
     *
     * @return IEnumerable 条件を満たす、入力シーケンスの要素を含むシーケンス
     */
    public function where(\Closure $predicate): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $predicate);
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
        return $this->addToLazyEval(__FUNCTION__, $predicate)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $predicate)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $value)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $index)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $predicate)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $predicate)->evalLazy();
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
        return $this->addToLazyEval(__FUNCTION__, $predicate)->evalLazy();
    }

    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの差集合を生成します。
     *
     * @param BaseEnumerable $second 最初のシーケンスにも含まれ、返されたシーケンスからは削除される要素を含むシーケンス
     *
     * @return IEnumerable 2 つのシーケンスの要素の差集合が格納されているシーケンス
     */
    public function except(BaseEnumerable $second): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $second->getSourceList());
    }

    /**
     * 既定の等値比較子を使用して値を比較することにより、2 つのシーケンスの積集合を生成します。
     *
     * @param BaseEnumerable $second 最初のシーケンスにも含まれる、返される一意の要素を含むシーケンス
     *
     * @return IEnumerable 2 つのシーケンスの積集合を構成する要素が格納されているシーケンス
     */
    public function intersect(BaseEnumerable $second): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $second->getSourceList());
    }

    /**
     * 既定の等値比較子を使用して、2 つのシーケンスの和集合を生成します。
     *
     * @param BaseEnumerable $second 和集合の 2 番目のセットを形成する一意の要素を含むシーケンス
     *
     * @return IEnumerable 2 つの入力シーケンスの要素 (重複する要素は除く) を格納しているシーケンス
     */
    public function union(BaseEnumerable $second): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $second->getSourceList());
    }

    /**
     * 2 つのシーケンスを連結します。
     *
     * @param BaseEnumerable $second 最初のシーケンスに連結するシーケンス
     *
     * @return IEnumerable 2 つの入力シーケンスの連結された要素が格納されているシーケンス
     */
    public function concat(BaseEnumerable $second): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $second->getSourceList());
    }

    /**
     * 2 つのシーケンスの対応する要素に対して、1 つの指定した関数を適用し、結果として 1 つのシーケンスを生成します。
     *
     * @param BaseEnumerable $second      マージする 2 番目のシーケンス
     * @param \Closure $resultSelector 2 つのシーケンスの要素をマージする方法を指定する関数
     *
     * @return IEnumerable 2 つの入力シーケンスのマージされた要素が格納されているシーケンス
     */
    public function zip(BaseEnumerable $second, \Closure $resultSelector): IEnumerable
    {
        return $this->addToLazyEval(__FUNCTION__, $second->getSourceList(), $resultSelector);
    }

    /**
     * 要素の型に対して既定の等値比較子を使用して要素を比較することで、2 つのシーケンスが等しいかどうかを判断します。
     *
     * @param BaseEnumerable $second 最初のシーケンスと比較するシーケンス
     *
     * @return bool 2 つのソースシーケンスが同じ長さで、それらに対応する要素が等しい場合は true。
     * それ以外の場合は false。
     */
    public function sequenceEqual(BaseEnumerable $second): bool
    {
        return $this->addToLazyEval(__FUNCTION__, $second->getSourceList())->evalLazy();
    }

    /**
     * シーケンスから新しい配列を作成します。
     *
     * @return array シーケンスから作成した新しい配列
     */
    public function toArray(): array
    {
        return $this->evalLazy()->getSourceList()->getArrayCopy();
    }

    /**
     * シーケンスから新しいリストを作成します。
     *
     * @return ListObject シーケンスから作成した新しいリスト
     */
    public function toList(): ListObject
    {
        return new ListObject($this->evalLazy()->getSourceList()->getArrayCopy());
    }

    /**
     * シーケンスから新しい連想リストを作成します。
     *
     * @param \Closure $key_selector   各要素からキーを抽出する関数
     * @param \Closure $value_selector 各要素から値を抽出する関数
     *
     * @return DictionaryObject シーケンスから作成した新しい連想リスト
     */
    public function toDictionary(\Closure $key_selector, \Closure $value_selector = null): DictionaryObject
    {
        $dic_list = [];

        foreach ($this->evalLazy()->getSourceList() as $key => $value) {
            $dic_list[$key_selector($key)] = isset($value_selector) ? $value_selector($value) : $value;
        }

        return new DictionaryObject($dic_list);
    }
}
