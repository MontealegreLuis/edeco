<?php
/**
 * Utility class for handling the displaying of model's URL properties
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
use Mandragora\Filter\FriendlyUrl;




/**
 * Utility class for handling the displaying of model's URL properties
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Model_Property
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Url
implements PropertyInterface
{
    /**
     * @var Mandragora_Filter_FriendlyUrl
     */
    protected static $urlFilter;

    /**
     * @return string
     */
    protected $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        if (!self::$urlFilter) {
            self::$urlFilter = new FriendlyUrl();
        }
        $this->url = $this->filter($url);
    }

    /**
     * @see Mandragora_String_Interface::__toString()
     */
    public function __toString()
    {
        return $this->url;
    }

    /**
     * @see Mandragora_Model_Property_Interface::render()
     */
    public function render()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function filter($url)
    {
        return self::$urlFilter->filter($url);
    }

}