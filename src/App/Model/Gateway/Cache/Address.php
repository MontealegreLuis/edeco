<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use App\Model\Gateway\AddressGateway;
use Mandragora\Gateway\Decorator\ProvidesCaching;
use Mandragora\Gateway\Decorator\ProvidesProxy;
use Zend_Cache as Cache;
use Edeco\Paginator\Property;
use Mandragora\Model\AbstractModel;

/**
 * Cache decorator for Address Gateway
 */
class Address extends AddressGateway
{
    use ProvidesCaching, ProvidesProxy;

    /**
     * @throws \Zend_Cache_Exception
     * @throws \Mandragora\Gateway\NoResultsFoundException
     */
    public function findOneById(int $id): array
    {
        $cacheId = 'address' . (int) $id;
        $address = $this->getCache()->load($cacheId);
        if (!$address) {
            $address = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($address, $cacheId);
        }
        return $address;
    }

    public function saveGeoPosition(int $id, array $geoPosition): void
    {
        $this->gateway->saveGeoPosition($id, $geoPosition);
        $this->getCache()->remove("property$id");
        $this->getCache()->remove("address$id");
        $this->getCache()->clean(
            Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            [Property::PROPERTIES_TAG,]
        );
    }

    public function insert(AbstractModel $address)
    {
        $this->gateway->insert($address);
        //Do not save this object in cache it'll be saved with all the
        //needed relationships in findOneById
        $cacheId = "property$address->id";
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            [Property::PROPERTIES_TAG,]
        );
    }

    public function update(AbstractModel $address)
    {
        $this->gateway->clearRelated();
        $this->gateway->update($address);
        //Remove this object from cache it'll be saved with all the
        //needed relationships in findOneById
        $cacheId = "address$address->id";
        $this->getCache()->remove($cacheId);
        $cacheId = "property$address->id";
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            [Property::PROPERTIES_TAG,]
        );
    }

    public function delete(AbstractModel $address)
    {
        $this->gateway->delete($address);
        $cacheId = "address$address->id";
        $this->getCache()->remove($cacheId);
        $cacheId = "property$address->id";
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            [Property::PROPERTIES_TAG,]
        );
    }
}
