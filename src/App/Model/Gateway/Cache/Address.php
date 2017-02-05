<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;
use Zend_Cache;
use Edeco\Paginator\Property;
use Mandragora\Model\AbstractModel;

/**
 * Cache decorator for Address Gateway
 */
class Address extends CacheAbstract
{
    /**
     * @return array
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $cacheId = 'address' . (int)$id;
        $address = $this->getCache()->load($cacheId);
        if (!$address) {
            $address = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($address, $cacheId);
        }
        return $address;
    }

    /**
    * @param int $id
    * @param array $geoPostition
    * @return void
    */
    public function saveGeoPosition($id, array $geoPostition)
    {
        $this->gateway->saveGeoPosition($id, $geoPostition);
        $this->getCache()->remove('property' . $id);
        $this->getCache()->remove('address' . $id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $address
     * @return void
     */
    public function insert(AbstractModel $address)
    {
        $this->gateway->insert($address);
        //Do not save this object in cache it'll be saved with all the
        //needed relationships in findOneById
        $cacheId = 'property' . $address->id;
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $address
     * @return void
     */
    public function update(AbstractModel $address)
    {
        $this->gateway->clearRelated();
        $this->gateway->update($address);
        //Remove this object from cache it'll be saved with all the
        //needed relationships in findOneById
        $cacheId = 'address' . $address->id;
        $this->getCache()->remove($cacheId);
        $cacheId = 'property' . $address->id;
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $address
     * @return void
     */
    public function delete(AbstractModel $address)
    {
        $this->gateway->delete($address);
        $cacheId = 'address' . $address->id;
        $this->getCache()->remove($cacheId);
        $cacheId = 'property' . $address->id;
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Property::PROPERTIES_TAG,)
        );
    }
}
