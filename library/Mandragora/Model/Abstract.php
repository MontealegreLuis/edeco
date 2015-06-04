<?php
/**
 * Base class for model objects
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
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class for model objects
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class Mandragora_Model_Abstract
implements     Mandragora_Array_Interface, Mandragora_String_Interface
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @var array
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $identifierValues;

    /**
     * @var array
     */
    protected $arrayValues;

    /**
     * @var array
     */
    protected static $sharedProperties = array('id' => 0, 'version' => null);

    /**
     * @param array $values = null
     */
    public function __construct(array $values = null)
    {
        $properties = array_merge(self::$sharedProperties, $this->properties);
        $this->properties = $properties;
        if (is_array($values)) {
            $this->import($values);
        }
        $this->identifierValues = null;
        $this->arrayValues = null;
    }

    /**
     * @param string $name
     * @param string $value
     * @throws Mandragora_Model_Property_Exception
     */
    public function __set($name, $value)
    {
        $this->isValidProperty($name);
        $setter = 'set'. ucfirst($name);
        if (is_callable(array($this, $setter))) {
            $this->$setter($value);
        } else {
            $this->properties[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @throws Mandragora_Model_Property_Exception
     */
    public function __get($name)
    {
        $this->isValidProperty($name);
        $getter = 'get'. ucfirst($name);
        if (is_callable(array($this, $getter))) {
            return $this->$getter();
        } else {
            return $this->properties[$name];
        }
    }

    /**
     * @param string $propertyName
     * @throws Mandragora_Model_Property_Exception
     */
    protected function isValidProperty($propertyName)
    {
        if (!array_key_exists($propertyName, $this->properties))  {
            throw new Mandragora_Model_Property_Exception(
                "Property '$propertyName' does not belong to class "
                . get_class($this)
            );
        }
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * @param string $name
     * @return void
     */
    public function __unset($name)
    {
        $this->isValidProperty($name);
        unset($this->properties[$name]);
    }

    /**
     * @param boolean $recursive = false
     * @return array
     */
    public function toArray($recursive = false)
    {
        if ($recursive) {
            $this->toArrayRecursive();
        } else {
            $this->arrayValues = $this->properties;
        }
        return $this->arrayValues;
    }

    /**
     * Only use the not null values and convert the inner objects to array when
     * possible
     *
     * @return void
     */
    protected function toArrayRecursive()
    {
        foreach ($this->properties as $name => $value) {
            if ($value instanceof Mandragora_Model_Property_Interface) {
                $this->arrayValues[$name] = (string)$this->__get($name);
            } else if ($value instanceof Mandragora_Array_Interface) {
                $object = $this->properties[$name];
                //Recurse toArray in the object properties too.
                $this->arrayValues[$name] = $object->toArray(true);
            //Add check to allow null values in optional fields
            } else  if (is_scalar($value) || is_null($value)) {
                $this->arrayValues[$name] = $value;
            }
        }
    }

    /**
     * @param array $values
     * @return void
     */
    public function fromArray(array $values)
    {
        $this->import($values);
    }

    /**
     * @param array $data
     * @return void
     */
    protected function import(array $values)
    {
        foreach ($this->properties as $name => $value) {
            if (array_key_exists($name, $values))  {
                $value = $values[$name];
                //Avoid setting empty strings
                $value = (is_string($value) && trim($value) === '')
                       ? null : $value;
                $this->__set($name, $value);
            }
        }
    }

    /**
     * @return array
     */
    public function getIdentifier()
    {
        if ($this->identifierValues == null) {
            foreach ($this->identifier as $field) {
                $this->identifierValues[$field] = $this->__get($field);
            }
        }
        return $this->identifierValues;
    }

}