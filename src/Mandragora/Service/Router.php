<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service;

/**
 * Factory for the service that holds all the default routes
 */
class Router
{
    /**
     * @return void
     */
    private function __construct() {}

    /**
     * @param string $routername
     * @return Mandragora_Service_Router_Interface
     */
    static public function factory($routerName)
    {
        $serviceName = sprintf('App\Service\Router\%s', $routerName);
        return new $serviceName();
    }
}
