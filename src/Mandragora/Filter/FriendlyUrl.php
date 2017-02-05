<?php
/**
 * Filter to transform a sentence into a friendly URL
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
 * @category   Application
 * @package    Mandragora
 * @subpackage Filter
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

namespace Mandragora\Filter;

use Zend_Filter_Interface;
use Mandragora\Filter\AccentsAndSpecialSymbols;
use Zend_Filter_StringToLower;
use Zend_Filter_Word_SeparatorToDash;




/**
 * Filter to transform a sentence into a friendly URL
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @category   Application
 * @package    Mandragora
 * @subpackage Filter
 * @history    25 may 2010
 *             LMV
 *             - Class creation
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