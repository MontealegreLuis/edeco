<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway\Decorator;

use Zend_Cache_Core as Cache;

trait ProvidesCaching
{
    /** @var Cache */
    protected $cache;

    public function setCache(Cache $cache): void
    {
        $this->cache = $cache;
    }

    protected function getCache(): Cache
    {
        return $this->cache;
    }
}
