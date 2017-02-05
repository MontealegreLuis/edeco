<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;
use Edeco\Paginator\Property as EdecoPaginatorProperty;
use Mandragora\Model\AbstractModel;
use Zend_Cache;

class Property extends CacheAbstract
{
    /**
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $cacheId = 'property' . (int)$id;
        $property = $this->getCache()->load($cacheId);
        if (!$property) {
            $property = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($property, $cacheId);
        }
        return $property;
    }

    /**
     * @param int $id
     * @return string
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findPropertyNameById($id)
    {
        $cacheId = 'property' . (int)$id;
        $property = $this->getCache()->load($cacheId);
        if (!$property) {
            $property = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($property, $cacheId);
        }
        return $property['name'];
    }

    /**
     * @return array
     */
    public function findOneByUrl($url)
    {
        $cacheId = 'property' . md5($url);
        $property = $this->getCache()->load($cacheId);
        if (!$property) {
            $property = $this->gateway->findOneByUrl($url);
            $this->getCache()->save(
                $property, $cacheId,
                array(EdecoPaginatorProperty::PROPERTIES_TAG,)
            );
        }
        return $property;
    }

        /**
     * @param int $propertyId
     * @return string
     */
    public function findAddressByPropertyId($propertyId)
    {
        $cacheId = 'property' . (int)$propertyId;
        $property = $this->getCache()->load($cacheId);
        if (!$property) {
            $property = $this->gateway->findOneById($propertyId);
            $this->getCache()->save($property, $cacheId);
        }
        // TODO: Find an elegant way to workaround null values in cache
        $addressReference = isset($property['addressReference'])
                          ? $property['addressReference']
                          : null;
        return array(
            'address' => $property['address'],
            'version' =>  $property['version'],
            'addressReference' => $addressReference,
        );
    }

    /**
     * @param Mandragora_Model_Abstract $property
     * @return void
     */
    public function delete(AbstractModel $property)
    {
        $this->gateway->delete($property);
        $cacheId = 'property' . $property->id;
        $this->getCache()->remove($cacheId);
        // Also clean data in default module
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(EdecoPaginatorProperty::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $property
     * @return void
     */
    public function insert(AbstractModel $property)
    {
        $this->gateway->insert($property);
        // Also clean data in default module
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(EdecoPaginatorProperty::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $property
     * @return void
     */
    public function update(AbstractModel $property)
    {
        $this->gateway->clearRelated();
        $this->gateway->update($property);
        $cacheId = 'property' . $property->id;
        $this->getCache()->remove($cacheId);
        // Also clean data in default module
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(EdecoPaginatorProperty::PROPERTIES_TAG,)
        );
    }
}
