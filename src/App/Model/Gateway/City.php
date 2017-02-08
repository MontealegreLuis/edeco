<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\DoctrineGateway;
use Doctrine_Core;

/**
 * Gateway for city model objects
 */
class City extends DoctrineGateway
{
    /**
     * @param int $stateId
     * @return array
     */
    public function findAllByStateId($stateId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->select('c.name')
              ->from($this->alias())
              ->where('c.State.id = :stateId');
        return $query->execute([':stateId' => (int) $stateId], Doctrine_Core::HYDRATE_ARRAY);
    }

    public function findAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias());
        return $query->fetchArray();
    }
}
