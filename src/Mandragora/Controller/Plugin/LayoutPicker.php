<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Plugin;

use Zend_Controller_Plugin_Abstract;
use Zend_Controller_Request_Abstract;
use Zend_Layout;

/**
 * Select the layout according to the module
 */
class LayoutPicker extends Zend_Controller_Plugin_Abstract
{
    /**
     * Select the layout according to the module
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        Zend_Layout::getMvcInstance()->setLayout($request->getModuleName());
    }
}
