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
class Cellphone
{
    /**
     * @param string $cellphoneNumber
     * @return string
     */
    public function cellphone($cellphoneNumber)
    {
        $areaCode = substr($cellphoneNumber, 0, 5);
        $prefix = substr($cellphoneNumber, 5, 2);
        $numberPart1 = substr($cellphoneNumber, 7, 2);
        $numberPart2 = substr($cellphoneNumber, 9, 2);
        $numberPart3 = substr($cellphoneNumber, 11, 2);
        return sprintf(
            '(%s) %s-%s-%s-%s',
            $areaCode, $prefix, $numberPart1, $numberPart2, $numberPart3
        );
    }
}
