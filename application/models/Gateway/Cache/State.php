<?php
class App_Model_Gateway_Cache_State
    extends Mandragora_Gateway_Decorator_CacheAbstract
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