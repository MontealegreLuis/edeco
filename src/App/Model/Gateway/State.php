<?php
/**
 * Gateway for city model objects
 *
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\DoctrineGateway;
use Doctrine_Core;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for city model objects
 */
class State extends DoctrineGateway
{
    /**
     * @return array
     */
    public function findAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias());
        $states = $query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $states;
    }

    /**
     * @return array
     */
    public function findAllMaps()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->innerJoin('s.Map m');
        $states = $query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $states;
    }

    /**
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByUrl($url)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('s.url = :url');
        $state = $query->fetchOne(
            array(':url' => (string)$url), Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$state) {
            throw new NoResultsFoundException(
            	"State with url '$url' cannot be found"
            );
        }
        return $state;
    }
}
