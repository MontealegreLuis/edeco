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
use Mandragora_Gateway_Doctrine_NoResultsFoundException;

/**
 * Gateway for picture model objects
 */
class Picture extends AbstractDoctrine
{
    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     */
    public function findOneByIdAndPropertyId($id, $propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.propertyId = :propertyId')
              ->andWhere('p.id = :id');
        $picture = $query->fetchOne(
            array(':id' => (int)$id, ':propertyId' => (int)$propertyId),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$picture) {
            throw new NoResultsFoundException(
                "The picture $id of property $propertyId cannot be found"
            );
        }
        return $picture;
    }

    /**
     * @param string $pictureName
     * @return array
     */
    public function findOneByName($pictureDescription)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.shortDescription = :shortDescription');
        $picture = $query->fetchOne(
            array(':shortDescription' => (string)$pictureDescription,),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$picture) {
            throw new Mandragora_Gateway_Doctrine_NoResultsFoundException(
                "Picture $pictureDescription not found"
            );
        }
        return $picture;
    }

    /**
     * @param int $propertyId
     * @return Doctrine_Query
     */
    public function getQueryFindAllByPropertyId($propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $params = array(':propertyId' => $propertyId);
        $query->from($this->alias())
              ->where('p.propertyId = :propertyId', $params);
        return $query;
    }
}
