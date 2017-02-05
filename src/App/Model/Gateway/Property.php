<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;
use Doctrine_Core;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for property model objects
 */
class Property extends AbstractDoctrine
{
    /**
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->innerJoin('p.Category c')
              ->leftJoin('p.Address a')
              ->leftJoin('a.City ci')
              ->leftJoin('ci.State s')
              ->andWhere('p.id = :id');
        $query->getSqlQuery();
        $property = $query->fetchOne(
            array(':id' => (int)$id), Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$property) {
            throw new NoResultsFoundException(
                "Property with id '$id' cannot be found"
            );
        }
        return $property;
    }

    /**
     * @return Doctrine_Query
     */
    public function getQueryFindAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->leftJoin('p.Address a')
              ->leftJoin('a.City c')
              ->leftJoin('c.State s')
              ->leftJoin('p.Picture pic');
        var_dump($query->getSqlQuery());die;
        return $query;
    }

    /**
     * @return array
     */
    public function findAllWebProperties()
    {
        $query = $this->dao->getTable()->createQuery();
        $select = 'p.name, p.url, p.availabilityFor, a.id, c.id, s.url, cat.url';
        $query->select($select)
              ->from($this->alias())
              ->innerJoin('p.Category cat')
              ->leftJoin('p.Address a')
              ->leftJoin('a.City c')
              ->leftJoin('c.State s')
              ->leftJoin('p.Picture pic')
              ->where('p.showOnweb = 1')
              ->orderBy('cat.url, s.url');
        $query->getSqlQuery();
        return $query->fetchArray();
    }

    /**
     * @param string $state
     * @param string $category
     * @param string $availability
     * @return Doctrine_Query
     */
    public function getQueryFindPropertiesBy($state, $category, $availability)
    {
        $query = $this->dao->getTable()->createQuery();
        $select = 'p.id, p.name, p.url, p.availabilityFor, p.description,'
                . 'a.*, c.*, s.*, cat.*, pic.*';
        $query->select($select)
              ->from($this->alias())
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->innerJoin('p.Category cat')
              ->where('cat.url = ?', $category)
              ->andWhere('s.url = ?', $state)
              ->andWhere('p.availabilityFor = ?', $availability)
              ->andWhere('p.showOnWeb = 1')
              ->leftJoin('p.Picture pic');
        $query->getSqlQuery();
        return $query;
    }

    /**
     * @return Doctrine_Query
     */
    public function getQueryFindAllWebPropertiesWithPictures()
    {
        $query = $this->dao->getTable()->createQuery();
        $select = 'p.id, p.name, p.url, a.*, c.*, s.*, cat.*, '
                . 'p.availabilityFor, p.description, pic.*';
        $query->select($select)
              ->from($this->alias())
              ->innerJoin('p.Address a')
              ->innerJoin('p.Category cat')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->innerJoin('p.Picture pic')
              ->where('p.showOnWeb = 1')
              ->orderBy('p.creationDate ASC')
        ;
        $query->getSqlQuery();
        return $query;
    }

    /**
     * @return array
     * @throws \Mandragora\Gateway\NoResultsFoundException
     */
    public function findOneByUrl($url)
    {
        $query = $this->dao->getTable()->createQuery();
        $select = 'p.id, p.name, p.description, p.price, p.url, '
            . 'p.availabilityFor, a.*, c.*, s.*, cat.*, pic.*, r.*, rp.*, '
            . 'catrp.*, ra.*, rc.*, rs.*';
        $query->select($select)
              ->from($this->alias())
              ->innerJoin('p.Category cat')
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->leftJoin('p.Picture pic')
              ->leftJoin('p.RecommendedProperty r')
              ->leftJoin('r.RecommendedProperty rp')
              ->leftJoin('rp.Category catrp')
              ->leftJoin('rp.Address ra')
              ->leftJoin('ra.City rc')
              ->leftJoin('rc.State rs')
              ->where('p.url = :url');
        $query->getSqlQuery();
        $property = $query->fetchOne([':url' => (string) $url], Doctrine_Core::HYDRATE_ARRAY);
        if (!$property) {
            throw new NoResultsFoundException("Property with URL $url cannot be found");
        }
        return $property;
    }

    /**
     * @param string $category
     * @return array
     */
    public function getCountByCategory($category)
    {
        $query = $this->dao->getTable()->createQuery();
        $select = 'p.id, a.id, c.stateId, s.name, s.url, cat.url, COUNT(*) AS propertyCount';
        $query->select($select)
              ->from($this->alias())
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->innerJoin('p.Category cat')
              ->where('cat.name = :category')
              ->andWhere('p.showOnWeb = 1')
              ->groupBy('c.stateId');
        $params = array(':category' => $category);
        $query->getSqlQuery();
        return $query->fetchArray($params);
    }

    /**
     * @param string $propertyName
     * @return array
     */
    public function findAllPropertiesWithNameLike($propertyName)
    {
    	$query = $this->dao->getTable()->createQuery();
    	$query->from($this->alias());
    	$parts = explode(' ', $propertyName);
    	$partsCount = count($parts);
    	for ($i = 0; $i < $partsCount; $i++) {
    	    if ($i == 0) {
    	      $query->andWhere('p.name LIKE ?');
    	    } else {
    		  $query->orWhere('p.name LIKE ?');
    	    }
    		$parts[$i] = '%' . $parts[$i] . '%';
    	}
    	$query->leftJoin('p.Picture pic');
    	$query->getSqlQuery();
        return $query->fetchArray($parts);
    }

    /**
     * @param int $stateId
     * @param int $propertyId
     * @return array
     */
    public function findRecommendedWebProperties($stateId, $propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->select('p.id, p.name, p.description, a.*, s.*, c.*');
        $subquery = '(SELECT r.recommendedPropertyId '
                  . 'FROM App_Model_Dao_RecommendedProperty r '
                  . 'WHERE r.propertyId = :propertyId)';
        $query->from($this->alias())
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->where('p.showOnWeb = 1')
              ->andWhere('s.id = :stateId')
              ->andWhere('p.id <> :propertyId')
              ->andWhere('p.id NOT IN ' . $subquery);
        $params = array(':stateId' => $stateId, ':propertyId' => $propertyId);
        return $query->fetchArray($params);
    }

    /**
     * @param string $propertyName
     * @param int $id
     * @return array
     */
    public function findAllWebPropertiesWithNameLike($propertyName)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->select('p.id, p.name, p.description, a.*, s.*, c.*');
        $query->from($this->alias());
        $parts = explode(' ', $propertyName);
        $partsCount = count($parts);
        for ($i = 0; $i < $partsCount; $i++) {
            if ($i == 0) {
                $query->andWhere('p.name LIKE ?');
            } else {
                $query->orWhere('p.name LIKE ?');
            }
            $parts[$i] = '%' . $parts[$i] . '%';
        }
        $query->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s');
        $query->getSqlQuery();
        return $query->fetchArray($parts);
    }

    /**
     * @param string $startDate
     * @param string $stopDate
     * @return array
     */
    public function findPropertiesInDateRange($startDate, $stopDate)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.creationDate BETWEEN :startDate AND :stopDate')
              ->innerJoin('p.Category cat')
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s');
        $params = array(':startDate' => $startDate, ':stopDate' => $stopDate);
        return $query->fetchArray($params);
    }
}
