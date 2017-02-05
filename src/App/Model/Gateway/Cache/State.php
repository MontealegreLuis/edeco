<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;

class State extends CacheAbstract
{
    /**
     * @return array
     */
    public function findAll()
    {
        $states = $this->getCache()->load('states');
        if (!$states) {
            $states = $this->gateway->findAll();
            $this->getCache()->save($states, 'states');
        }
        return $states;
    }
}
