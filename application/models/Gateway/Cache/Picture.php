<?php
class App_Model_Gateway_Cache_Picture
    extends Mandragora_Gateway_Decorator_CacheAbstract
{
    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     */
    public function findOneByIdAndPropertyId($id, $propertyId)
    {
        $picture = $this->getCache()->load('picture' . $id);
        if (!$picture) {
            $picture = $this->gateway
                            ->findOneByIdAndPropertyId($id, $propertyId);
            $this->getCache()->save($picture, 'picture' . $id);
        }
        return $picture;
    }

    /**
     * @param Mandragora_Model_Abstract $project
     */
    public function insert(Mandragora_Model_Abstract $picture)
    {
        $this->gateway->insert($picture);
        $this->getCache()->save($picture->toArray(), 'picture' . $picture->id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Edeco_Paginator_Property::PROPERTIES_TAG,
            )
        );
    }

    /**
     * @param Mandragora_Model_Abstract $project
     */
    public function update(Mandragora_Model_Abstract $picture)
    {
        $this->gateway->update($picture);
        $this->getCache()->save($picture->toArray(), 'picture' . $picture->id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Edeco_Paginator_Property::PROPERTIES_TAG,
            )
        );
    }

    /**
     * @param Mandragora_Model_Abstract $project
     */
    public function delete(Mandragora_Model_Abstract $picture)
    {
        $this->gateway->delete($picture);
        $this->getCache()->remove('picture' . $picture->id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Edeco_Paginator_Property::PROPERTIES_TAG,
            )
        );
    }

}