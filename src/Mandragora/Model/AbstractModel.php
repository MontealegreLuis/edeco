<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model;

use Mandragora\ArrayConversion\ArrayInterface;
use Mandragora\String\StringInterface;
use Mandragora\Model\Property\NonExistingProperty;
use Mandragora\Model\Property\PropertyInterface;

/**
 * Base class for model objects
 */
abstract class AbstractModel implements ArrayInterface, StringInterface
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
            throw new NonExistingProperty(
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
            if ($value instanceof PropertyInterface) {
                $this->arrayValues[$name] = (string) $this->__get($name);
            } else if ($value instanceof ArrayInterface) {
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
                $value = (is_string($value) && trim($value) === '') ? null : $value;
                $value = $value instanceof PropertyInterface ? (string) $value : $value;
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
