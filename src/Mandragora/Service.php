<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

/**
 * Factory for model's services
 */
class Service
{
    private function __construct() {}

    /**
     * @param string $modelName
     * @return Mandragora_Service_Abstract
     */
    public static function factory($modelName)
    {
        $serviceClass = sprintf('App\Service\%s', $modelName);
        return new $serviceClass($modelName);
    }
}
