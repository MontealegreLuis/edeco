<?php
/**
 * Utility class for handling model's password properties
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




/**
 * Utility class for handling model's password properties
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Model_Property
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Password
    implements PropertyInterface
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $password
     */
    public function __construct($password)
    {
        if (strlen($password) != 40) {
            $this->password = sha1($password);
        } else {
            $this->password = $password;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function render()
    {
        return '*****'; //Do not show passwords in the view layer;
    }

}