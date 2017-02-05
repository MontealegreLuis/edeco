<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway\Decorator;

use Mandragora\Gateway\GatewayInterface;
use Zend_Cache_Core;
use Mandragora\Gateway\GatewayException;

abstract class CacheAbstract
{
    /** @var GatewayInterface */
    protected $gateway;

    /** @var Zend_Cache_Core */
    protected $cache;

    public function __construct(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param Zend_Cache_Core $cache
     * @return void
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return Zend_Cache_Core
     */
    protected function getCache()
    {
        return $this->cache;
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

    /**
     * @param $method
     * @return bool
     */
    private function isACallable($method)
    {
        return method_exists($this->gateway, $method)
            && is_callable([$this->gateway, $method]);
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    private function call($method, array $args)
    {
        return call_user_func_array([$this->gateway, $method], $args);
    }
}
