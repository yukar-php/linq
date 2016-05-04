<?php
namespace Yukar\Linq\Collections;

use Yukar\Linq\Enumerable\TCalculation;
use Yukar\Linq\Enumerable\TExtract;
use Yukar\Linq\Enumerable\TInspection;
use Yukar\Linq\Enumerable\TSet;
use Yukar\Linq\Interfaces\IEnumerable;

/**
 * 各要素について遅延評価をすることのできるオブジェクトのリストを実装するための抽象クラスです。
 */
abstract class BaseEnumerable implements IEnumerable
{
    use TCalculation, TInspection, TExtract, TSet;

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

    public function __invoke(): self
    {
        return $this->setSourceList(
            $this->evalLazy(),
            $this->getSourceList()->getFlags(),
            $this->getSourceList()->getIteratorClass()
        );
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
        foreach ($this->getLazyEvalList() as $key => $lazy_eval_method) {
            $result_value = $lazy_eval_method($this);

            unset($this->lazy_eval_list[$key]);

            if (is_array($result_value) !== true) {
                return $result_value;
            }

            $this->getSourceList()->exchangeArray($result_value);
        }

        return $this;
    }
}
