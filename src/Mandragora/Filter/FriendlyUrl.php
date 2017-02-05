<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Filter;

use Zend_Filter_Interface;
use Mandragora\Filter\AccentsAndSpecialSymbols;
use Zend_Filter_StringToLower;
use Zend_Filter_Word_SeparatorToDash;

/**
 * Filter to transform a sentence into a friendly URL
 */
class FriendlyUrl implements Zend_Filter_Interface
{
    /**
     * Apply the Mandragora_Filter_AccentsAndSpecialSymbols filter, lower case
     * the string and finally change spaces for dashes
     *
     * @param string $value
     *      The string to be filtered
     * @return string
     *      The filtered string
     */
    public function filter($value)
    {
        /* Remove acents */
        $filterAccents = new AccentsAndSpecialSymbols();
        $value = $filterAccents->filter((string)$value);

        /* Transform to lower case */
        $filterLowerCase = new Zend_Filter_StringToLower(
            array('encoding' => 'utf-8')
        );
        $value = $filterLowerCase->filter($value);

        /* Use dashes as separators */
        $filter = new Zend_Filter_Word_SeparatorToDash();

        return $filter->filter($value);
    }
}
