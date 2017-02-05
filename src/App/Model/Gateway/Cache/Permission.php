<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;

class Permission extends CacheAbstract
{
    /**
     * @return array
     */
    public function findAll()
    {
        $permissions = $this->getCache()->load('permissions');
        if (!$permissions) {
            $permissions = $this->gateway->findAll();
            $this->getCache()->save($permissions, 'permissions');
        }
        return $permissions;
    }
}
