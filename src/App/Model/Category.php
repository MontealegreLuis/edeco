<?php
/**
* Category model
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
* @package    App
* @subpackage Model
* @author     LMV <luis.montealegre@mandragora-web-systems.com>
* @copyright  Mandrágora Web-Based Systems 2010
* @version    SVN: $Id$
*/

/**
 * Category model
 *
 * @property integer $id
 * @property string $name
 * @property integer $version
 *
 * @package    App
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   App_Model_Category
extends Mandragora_Model_Abstract
{
    /**
     * @var array
     */
    protected $properties = array('name' => null, 'url' => null);

    /**
     * @var array
     */
    protected $identifier = array('id');

    /**
     * @param string $url
     * @return void
     */
    public function setUrl($url)
    {
        $url = new Mandragora_Model_Property_Url($this->properties['name']);
        $this->properties['url'] = $url;
    }

    /**
     * @see Mandragora_String_Interface::__toString()
     */
    public function __toString()
    {
        return $this->properties['name'];
    }

}