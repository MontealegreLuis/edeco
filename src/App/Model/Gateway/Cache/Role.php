<?php
class App_Model_Gateway_Cache_Role
    extends Mandragora_Gateway_Decorator_CacheAbstract
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