<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;
use Mandragora\Model\AbstractModel;
use Zend_Cache;
use Edeco\Paginator\Property;

/**
 * Cache decorator for RecommendedProperty's Gateway
 */
class RecommendedProperty
extends CacheAbstract
{
    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function findOneBy($id, $propertyId)
    {
        $cacheId = 'recommendedProperty' . (int)$id . '_' . (int)$propertyId;
        $recommendedProperty = $this->getCache()->load($cacheId);
        if (!$recommendedProperty) {
            $recommendedProperty = $this->gateway
                                        ->findOneBy((int)$id, (int)$propertyId);
            $this->getCache()->save($recommendedProperty, $cacheId);
        }
        return $recommendedProperty;
    }

    /**
     * @param AbstractModel $recommendedProperty
     * @return void
     */
    public function delete(AbstractModel $recommendedProperty)
    {
        $cacheId = 'recommendedProperty'
            . (int) $recommendedProperty->propertyId . '_'
            . (int) $recommendedProperty->recommendedPropertyId;
        $this->gateway->delete($recommendedProperty);
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, [
            'property' . $recommendedProperty->propertyId,
            // Also clean data in default module
            Property::PROPERTIES_TAG,
        ]);
    }

    /**
    * @param array $properties
    * @param int $propertyId
    */
    public function insertProperties(array $properties, $propertyId)
    {
        $this->gateway->insertProperties($properties, $propertyId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $propertyId,
                // Also clean data in default module
                Property::PROPERTIES_TAG,
            )
        );
    }
}
