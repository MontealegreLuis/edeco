<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Zend_Measure_Area;

/**
 * Utility class for handling model's square meter properties
 */
class SquareMeter implements PropertyInterface
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @var Zend_Measure_Area
     */
    protected static $area;

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
        if (!self::$area) {
            self::$area = new Zend_Measure_Area($this->value);
        } else {
            self::$area->setValue($this->value);
        }
        return self::$area->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->formatValue();
        return self::$area->getValue();
    }
}
