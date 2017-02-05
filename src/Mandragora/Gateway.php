<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

/**
 * Factory for model's gateways
 */
class Gateway
{
    /**
     * @return void
     */
    private function __construct() {}

    /**
     * @param string $model
     * @param string $namespace = ''
     * @return Mandragora_Gateway_Interface
     */
    public static function factory($model)
    {
        $gatewayName = sprintf('App\Model\Gateway\%s', $model);
        $daoName = sprintf('App\Model\Dao\%sDao', $model);
        return new $gatewayName(new $daoName());
    }
}
