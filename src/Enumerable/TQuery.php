<?php
namespace Yukar\Linq\Enumerable;

/**
 * 
 */
trait TQuery
{
    /**
     * 
     * 
     * @param \ArrayObject $source
     * @param \Closure $selector
     *
     * @return \ArrayObject
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
     * 
     * 
     * @param \ArrayObject $source
     *
     * @return \ArrayObject
     */
    public function distinct(\ArrayObject $source): \ArrayObject
    {
        return new \ArrayObject(array_unique($source->getArrayCopy(), SORT_REGULAR));
    }
    
    /**
     * 
     * 
     * @param \ArrayObject $source
     * @param \Closure $predicate
     *
     * @return \ArrayObject
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