<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service;

/**
 * Factory for the service that will setup the main navigation menu
 */
class Language
{
    /**
     * @return void
     */
    private function __construct() {}

    /**
     * @param string $className
     * @return Mandragora_Service_Language_Interface
     */
    static public function factory($className)
    {
        $serviceName = sprintf('App\Service\Language\%s', $className);
        return new $serviceName();
    }
}
