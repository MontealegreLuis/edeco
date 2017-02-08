<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Zend_Measure_Length;

/**
 * Utility class for handling model's meter properties
 */
class Meter implements PropertyInterface
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @var Zend_Measure_Length
     */
    protected static $length;

    /**
     * @param float $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function render()
    {
        return (string) $this->formatValue();
    }

    /**
     * @return string
     * @throws \Zend_Measure_Exception
     */
    public function formatValue()
    {
        if (!self::$length) {
            self::$length = new Zend_Measure_Length($this->value);
        } else {
            self::$length->setValue($this->value);
        }
        return self::$length->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->formatValue();
    }
}
