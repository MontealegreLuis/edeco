<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Collection;

use Iterator;
use ArrayAccess;
use Countable;
use Mandragora\ArrayConversion\ArrayInterface;
use BadMethodCallException;

/**
 * Base class for collections
 */
abstract class AbstractCollection implements Iterator, ArrayAccess, Countable, ArrayInterface
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @var array
     */
    private $rows;

    /**
     * @var array
     */
    private $objects;

    /**
     * @var int
     */
    private $current;

    /**
     * @param array $rows = array()
     */
    public function __construct(array $rows = array())
    {
        $this->rows = $rows;
        $this->count = count($rows);
        $this->current = 0;
        $this->objects = array();
    }

    /**
     * @return Mandragora_Model_Abstract
     */
    abstract protected function createModel(array $data);

    /**
     * @return void
     */
    public function rewind()
    {
        $this->current = 0;
    }

    /**
     * @return Mandragora_Model_Abstract
     */
    public function current()
    {
        return $this->row($this->current);
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->current;
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->current;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return !is_null($this->current());
    }

    /**
     * @param int $offset
     * @param mixed $value
     * @throws BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('This collection is read-only');
    }

    /**
     * @param int $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return $this->row($offset) != null;
    }

    /**
     * @param int $offset
     * @throws BadMethodCallException
     */
    public function offsetUnset($offset) {
        throw new BadMethodCallException('This collection is read-only');
    }

    /**
     * @param int $offset
     * @return Mandragora_Model_Abstract | null
     */
    public function offsetGet($offset) {
        return $this->row($offset);
    }

    /**
     * @param int $index
     * @return Mandragora_Model_Abstract
     */
    public function row($index)
    {
        if ($index >= $this->count || $index < 0) {
            return null;
        }
        if (isset($this->objects[$index]) ) {
            return $this->objects[$index];
        }
        if (isset($this->rows[$index])) {
            $this->objects[$index] = $this->createModel($this->rows[$index]);
            return $this->objects[$index];
        }
        return null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->rows;
    }
}
