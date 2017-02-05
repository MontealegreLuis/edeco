<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Zend_View_Helper_Currency;

/**
 * Utility class for handling model's currency properties
 */
class Currency implements PropertyInterface
{
    /**
     * @var Zend_View_Helper_Currency
     */
    static protected $currencyHelper;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @param float $amount
     */
    public function __construct($amount)
    {
        $this->amount = (float)$amount;
    }

    /**
     * @return Zend_View_Helper_Currency
     */
    static protected function getHelper()
    {
        if (!self::$currencyHelper) {
            self::$currencyHelper = new Zend_View_Helper_Currency();
        }
        return self::$currencyHelper;
    }

    /**
     * @return string
     */
    public function format()
    {
        return self::getHelper()->currency($this->amount);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->amount;
    }

    public function render()
    {
        return $this->format();
    }
}
