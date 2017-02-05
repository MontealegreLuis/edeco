<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Plugin;

use Zend_Controller_Plugin_Abstract;
use Zend_Controller_Request_Abstract;
use Mandragora\Service\Menu;

/**
 * Select the navigation menu accordingly
 */
class MenuSelector extends Zend_Controller_Plugin_Abstract
{
    /**
     * Select the menu accordingly
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $menuService = Menu::factory();
        $menuService->selectMenu($request);
    }
}
