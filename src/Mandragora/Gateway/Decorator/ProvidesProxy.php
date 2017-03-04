<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway\Decorator;

trait ProvidesProxy
{
    private $gateway;

    public function __construct($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws \Mandragora\Gateway\GatewayException
     */
    public function __call($method, $args)
    {
        if ($this->isACallable($method)) {
            return $this->call($method, $args);
        }
        throw new GatewayException("Method '$method' cannot be called or does not exist");
    }

    private function isACallable(string $method): bool
    {
        return method_exists($this->gateway, $method)
            && is_callable([$this->gateway, $method]);
    }

    /**
     * @return mixed
     */
    private function call(string $method, array $args)
    {
        return $this->gateway->$method(...$args);
    }
}
