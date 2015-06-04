<?php
class App_Model_Gateway_Cache_Permission
    extends Mandragora_Gateway_Decorator_CacheAbstract
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