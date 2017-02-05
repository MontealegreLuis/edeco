<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Filter;

use Zend_Filter_Interface;

/**
 * It converts a string into a float value
 */
class Float implements Zend_Filter_Interface
{
    /**
     * @param  string $value
     * @return integer
     */
    public function filter($value)
    {
        return (float) ((string) $value);
    }
}