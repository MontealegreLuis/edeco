<?php
/**
 * Utility class for handling model's currency properties
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
 * @subpackage Model_Property
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

namespace Mandragora\Model\Property;

use Mandragora\Model\Property\PropertyInterface;
use Zend_View_Helper_Currency;




/**
 * Utility class for handling model's currency properties
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Model_Property
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Currency
    implements PropertyInterface
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