<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service;

use App\Service\Menu\Selector;

/**
 * Factory for the service that will setup the main navigation menu
 */
class Menu
{
    private function __construct() {}

    /**
     * @return Mandragora_Service_Menu_Interface
     */
    static public function factory()
    {
        return new Selector();
    }
}
