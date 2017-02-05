<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Menu;

use Zend_Controller_Request_Abstract;

/**
 * Interface for the service that will setup the main navigation menu
 */
interface MenuInterface
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function selectMenu(Zend_Controller_Request_Abstract $request);
}
