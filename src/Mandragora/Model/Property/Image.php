<?php
/**
 * Utility class for handling the displaying of model's image properties
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
use Zend_Layout;




/**
 * Utility class for handling the displaying of model's image properties
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Model_Property
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Image
    implements PropertyInterface
{
    /**
     * @var Mandragora_Filter_FriendlyUrl
     */
    protected $urlFilter;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var string
     */
    protected $publicDirectory;

    /**
     * @param string $name
     * @param string $publicDirectory
     * @param string $extension = '.jpg'
     */
    public function __construct(
        $name, $publicDirectory, $extension = '.jpg'
    )
    {
        $this->name = $name;
        $this->extension = $extension;
        $this->publicDirectory = $publicDirectory;
        $this->urlFilter = new FriendlyUrl();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->urlFilter->filter($this->name) . $this->extension;
    }

    /**
     * Create <img /> element
     *
     * @return string
     */
    public function htmlify()
    {
        $view = Zend_Layout::getMvcInstance()->getView();
        $src = $this->publicDirectory . '/' . $this;
        return sprintf(
            '<img alt="%s" src="%s" />', $this->name, $view->baseUrl($src)
        );
    }

    public function render()
    {
        return $this->htmlify();
    }

}