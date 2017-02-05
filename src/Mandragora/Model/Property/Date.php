<?php
/**
 * Utility class for handling model's date properties
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
use Zend_Date;




/**
 * Utility class for handling model's date properties
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Model_Property
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Date
    implements PropertyInterface
{
    /**
     * @var Zend_Date
     */
    protected $date;

    /**
     * @var string
     */
    protected $renderFormat;

    /**
     * @var string
     */
    protected $stringFormat;

    /**
     * @param string $date
     * @param string $renderFormat = Zend_Date::DATE_FULL
     * @param string $stringFormat = 'YYYY-MM-dd'
     */
    public function __construct(
        $date,
        $renderFormat = Zend_Date::DATE_FULL,
        $stringFormat = 'YYYY-MM-dd'
    )
    {
        $this->renderFormat = $renderFormat;
        $this->stringFormat = $stringFormat;
        $this->date = new Zend_Date($date, $this->stringFormat);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date->toString($this->stringFormat);
    }

    /**
     * @return string
     */
    public function render()
    {
        return ucfirst($this->date->toString(Zend_Date::DATE_FULL));
    }

}