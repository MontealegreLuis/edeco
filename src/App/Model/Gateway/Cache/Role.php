<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;

class Role extends CacheAbstract
{
    /**
     * @return array
     */
    public function findAll()
    {
        $roles = $this->getCache()->load('roles');
        if (!$roles) {
            $roles = $this->gateway->findAll();
            $this->getCache()->save($roles, 'roles');
        }
        return $roles;
    }
}
