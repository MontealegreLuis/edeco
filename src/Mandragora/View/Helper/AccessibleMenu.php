<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\View\Helper;

use Zend_View_Helper_Navigation_HelperAbstract;
use Zend_Navigation_Container;

/**
 * View helper to show the accesskey attribute in links
 */
class AccessibleMenu extends Zend_View_Helper_Navigation_HelperAbstract
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
