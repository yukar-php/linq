<?php
namespace Yukar\Linq;

/**
 * LINQの操作対象となるリストの取得及び設定に関するを操作する機能を提供します。
 */
abstract class BaseListProperty
{
    private $list = null;

    protected function setSourceList(\ArrayObject $list): self
    {
        $this->list = $list;
        
        return $this;
    }

    /**
     * LINQの操作対象となるリストのオブジェクトを取得します。
     *
     * @return \ArrayObject LINQの操作対象となるリストのオブジェクト
     */
    public function getSourceList(): \ArrayObject
    {
        if ($this->list instanceof \ArrayObject === false) {
            throw new \LogicException();
        }

        return $this->list;
    }
}
