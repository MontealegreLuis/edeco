<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway\Decorator;

use Mandragora\Gateway\GatewayInterface;
use Zend_Cache_Core;
use Mandragora\Gateway\Exception;

abstract class CacheAbstract
{
    /**
     * @var Mandragora_Gateway_Interface
     */
    protected $gateway;

    /**
     * @var Zend_Cache
     */
    protected $cache;

    /**
     * @param Mandragora_Gateway_Interface $gateway
     */
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
     * @param string $function
     * @param array $args
     */
    public function __call($method, $args)
    {
        if (method_exists($this->gateway, $method)
            && is_callable(array($this->gateway, $method))) {
            return call_user_func_array(array($this->gateway, $method), $args);
        } else {
            throw new Exception(
                "Method '$method' cannot be called or does not exist"
            );
        }
    }

}