<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;

class City extends CacheAbstract
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
