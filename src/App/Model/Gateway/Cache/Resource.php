<?php
class App_Model_Gateway_Cache_Resource
    extends Mandragora_Gateway_Decorator_CacheAbstract
{
    /**
     * @return array
     */
    public function findAll()
    {
        $resources = $this->getCache()->load('resources');
        if (!$resources) {
            $resources = $this->gateway->findAll();
            $this->getCache()->save($resources, 'resources');
        }
        return $resources;
    }
}