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

class Picture extends CacheAbstract
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
    public function insert(AbstractModel $picture)
    {
        $this->gateway->insert($picture);
        $this->getCache()->save($picture->toArray(), 'picture' . $picture->id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Property::PROPERTIES_TAG,
            )
        );
    }

    /**
     * @param Mandragora_Model_Abstract $project
     */
    public function update(AbstractModel $picture)
    {
        $this->gateway->update($picture);
        $this->getCache()->save($picture->toArray(), 'picture' . $picture->id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Property::PROPERTIES_TAG,
            )
        );
    }

    /**
     * @param Mandragora_Model_Abstract $project
     */
    public function delete(AbstractModel $picture)
    {
        $this->gateway->delete($picture);
        $this->getCache()->remove('picture' . $picture->id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Property::PROPERTIES_TAG,
            )
        );
    }
}