<?php
/**
 * Base class for collections
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Collection
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class for collections
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Collection
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class Mandragora_Collection_Abstract
implements     Iterator, ArrayAccess, Countable, Mandragora_Array_Interface
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