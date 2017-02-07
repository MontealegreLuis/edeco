<?php
/**
 * Gateway for RecommendedProperty model objects
 *
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\RecommendedPropertyDao;
use Mandragora\Gateway\Doctrine\DoctrineGateway;
use Doctrine_Core;
use Mandragora\Gateway\NoResultsFoundException;
use Doctrine_Collection;

/**
 * Gateway for RecommendedProperty model objects
 */
class RecommendedProperty extends DoctrineGateway
{
    /**
     * @return \Doctrine_Query
     */
    public function getQueryFindAll($id)
    {
        $query = $this->dao->getTable()->createQuery();
        $params = array(':propertyId' => (int)$id);
        $query->from($this->alias())
              ->where('r.propertyId = :propertyId', $params)
              ->innerJoin('r.RecommendedProperty p')
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s');
        return $query;
    }

    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     * @throws \Mandragora\Gateway\NoResultsFoundException
     */
    public function findOneBy($id, $propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('r.propertyId = :id')
              ->andWhere('r.recommendedPropertyId = :propertyId');
        $params = [':id' => (int)$id, ':propertyId' => (int)$propertyId];
        $recommendedProperty = $query->fetchOne(
            $params, Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$recommendedProperty) {
            throw new NoResultsFoundException(
                "RecommendedProperty with id '$id' cannot be found"
            );
        }
        return $recommendedProperty;
    }

    /**
     * @param array $properties
     * @param int $propertyId
     */
    public function insertProperties(array $properties, $propertyId)
    {
        $collection = new Doctrine_Collection(RecommendedPropertyDao::class);
        foreach ($properties as $recommendedPropertyId) {
            $property = new RecommendedPropertyDao();
            $property->propertyId = $propertyId;
            $property->recommendedPropertyId = $recommendedPropertyId;
            $collection->add($property);
        }
        $collection->save();
    }
}
