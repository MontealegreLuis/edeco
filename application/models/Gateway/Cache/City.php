<?php
class   App_Model_Gateway_Cache_City
extends Mandragora_Gateway_Decorator_CacheAbstract
{
    /**
     * @param string $stateName
     * @return array
     */
    public function findAllByStateId($stateId)
    {
        $cities = $this->getCache()->load('cities' . $stateId);
        if (!$cities) {
            $cities = $this->gateway->findAllByStateId($stateId);
            $this->getCache()->save($cities, 'cities' . $stateId);
        }
        return $cities;
    }

}