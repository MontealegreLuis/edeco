<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Mandragora\Model\Property\PropertyInterface;
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
        return (string)$this->formatValue();
    }

    /**
     * @return void
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
        $this->formatValue();
        return self::$length->getValue();
    }
}
