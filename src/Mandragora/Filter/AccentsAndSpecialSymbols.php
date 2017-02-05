<?php
/**
 * Replaces accents and Ñ's with vouels and n, respectively, it also removes
 * non alphanumeric characters
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Filter
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

namespace Mandragora\Filter;

use Zend_Filter_Interface;
use Zend_Filter_Alnum;




/**
 * Replaces accents and Ñ's with vouels and n, respectively, it also removes
 * non alphanumeric characters
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Filter
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class AccentsAndSpecialSymbols
    implements Zend_Filter_Interface
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