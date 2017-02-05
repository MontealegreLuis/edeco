<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Filter;

use Zend_Filter_Interface;
use Zend_Filter_Alnum;

/**
 * Replaces accents and Ñ's with vouels and n, respectively, it also removes
 * non alphanumeric characters
 */
class AccentsAndSpecialSymbols implements Zend_Filter_Interface
{
    /**
     * Filter accents, non alphanumeric symbols and the letter ñ (case
     * insensitive)
     *
     * @param string $value
     *      The string to be filtered
     * @return string
     *      The filtered string
     */
    public function filter($value)
    {
        /* First remove non alphanumeric symbols */
        $filtro = new Zend_Filter_Alnum(true);
        $value = $filtro->filter((string)$value);

        /* Then the accents, the ñ and the ü */
        return $this->removeAccents($value);
    }

    /**
     * Replace the accents, the ñ and the ü for an equivalent in the english
     * alphabet
     *
     * @param string $value
     *      The string to be filtered
     * @return string
     *      The filtered string
     */
    protected function removeAccents($value)
    {
        $accents = array(
            'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ü', 'Ü'
        );
        $replacements = array(
            'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'u', 'U'
        );

        return str_replace($accents, $replacements, (string)$value);
    }
}
