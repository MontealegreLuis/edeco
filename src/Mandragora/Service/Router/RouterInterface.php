<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Router;

/**
 * Interface for the service that holds all the default routes
 */
interface RouterInterface
{
    /**
     * @param string $key
     * @return array
     */
    public function getDefaultRoute($key);
}
