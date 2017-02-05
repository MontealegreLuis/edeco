<?php
/**
 * Helper for displaying the current date
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

namespace Mandragora\View\Helper;

use Zend_Controller_Front;
use Zend_Locale;
use Zend_Registry;
use Zend_Date;




/**
 * Helper for displaying the current date
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @category   Library
 * @package    Mandragora
 */
class DateFormat
{
    /**
     * @var string
     */
    protected $locale;

    public function __construct()
    {
        $fc = Zend_Controller_Front::getInstance();
        $cache = $fc->getParam('bootstrap')
                    ->getResource('cachemanager')
                    ->getCache('default');
        Zend_Locale::setCache($cache);
        $this->locale = Zend_Registry::get('Zend_Locale');
    }

    /**
     * @return Mandragora_View_Helper_DateFormat
     */
    public function dateFormat()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function year()
    {
        $currentDate = new Zend_Date(null, null, $this->locale);
        return $currentDate->toString(Zend_Date::YEAR);
    }

    /**
     * @return string
     */
    public function full()
    {
        $currentDate = new Zend_Date(null, null, $this->locale);
        return $currentDate->toString(Zend_Date::DATE_FULL);
    }

}