<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\View\Helper;

/**
 * Helper for formatting telephone numbers
 */
class Telephone
{
    /**
     * @param string $telephoneNumber
     * @return string
     */
    public function telephone($telephoneNumber)
    {
        if (trim($telephoneNumber) != '') {
            $areaCode = substr($telephoneNumber, 0, 3);
            $prefix = substr($telephoneNumber, 3, 1);
            $numberPart1 = substr($telephoneNumber, 4, 2);
            $numberPart2 = substr($telephoneNumber, 6, 2);
            $numberPart3 = substr($telephoneNumber, 8, 2);
            return sprintf(
                '(%s) %s-%s-%s-%s',
                $areaCode, $prefix, $numberPart1, $numberPart2, $numberPart3
            );
        }
    }
}
