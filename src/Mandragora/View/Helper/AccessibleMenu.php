<?php
/**
 * View helper to show the accesskey attribute in links
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
 * @subpackage View_Helper
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * View helper to show the accesskey attribute in links
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage View_Helper
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_View_Helper_AccessibleMenu
    extends Zend_View_Helper_Navigation_HelperAbstract
{
    /**
     * @param Zend_Navigation_Page $page
     * @return Mandragora_View_Helper_AccessibleMenu
     */
    public function accessibleMenu()
    {
        return $this;
    }

    /**
     * @param Zend_Navigation_Container $page = null
     * @return string
     */
    public function render(Zend_Navigation_Container $page = null)
    {
        $label = $page->getLabel();
        $title = $page->getTitle();
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'accesskey' => $page->accesskey
        );
        if ($page->isActive()) {
            $attribs['class'] = 'active';
        }
        if ($href = $page->getHref()) {
            $element = 'a';
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
        } else {
            $element = 'span';
        }
        return '<' . $element . $this->attribs($attribs) . '>'
             . $this->view->escape($label)
             . '</' . $element . '>';
    }

    /**
     * @param array $attribs
     * @return string
     */
    public function attribs(array $attribs)
    {
        $xhtml = '';
        foreach ($attribs as $key => $value) {
        	if ($value !== null) {
        	    $xhtml .= " $key=\"$value\"";
        	}
        }
        return $xhtml;
    }

}