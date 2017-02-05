<?php
/**
 * Gateway for city model objects
 *
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;
use Doctrine_Core;

/**
 * Gateway for city model objects
 */
class City extends AbstractDoctrine
{
    /**
     * @param string $stateName
     * @return array
     */
    public function findAllByStateId($stateId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->select('c.name')
              ->from($this->alias())
              ->where('c.State.id = :stateId');
        $cities = $query->execute(
            array(':stateId' => (int)$stateId), Doctrine_Core::HYDRATE_ARRAY
        );
        return $cities;
    }

    public function findAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias());
        $cities = $query->fetchArray();
        return $cities;
    }
}
