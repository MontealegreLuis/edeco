<?php
class   App_Model_Gateway_Cache_Property
extends Mandragora_Gateway_Decorator_CacheAbstract
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
                array(Edeco_Paginator_Property::PROPERTIES_TAG,)
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
    public function delete(Mandragora_Model_Abstract $property)
    {
        $this->gateway->delete($property);
        $cacheId = 'property' . $property->id;
        $this->getCache()->remove($cacheId);
        // Also clean data in default module
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $property
     * @return void
     */
    public function insert(Mandragora_Model_Abstract $property)
    {
        $this->gateway->insert($property);
        // Also clean data in default module
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $property
     * @return void
     */
    public function update(Mandragora_Model_Abstract $property)
    {
        $this->gateway->clearRelated();
        $this->gateway->update($property);
        $cacheId = 'property' . $property->id;
        $this->getCache()->remove($cacheId);
        // Also clean data in default module
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

}