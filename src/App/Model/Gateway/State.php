<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\DoctrineGateway;
use Doctrine_Core as HydrationType;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for city model objects
 */
class State extends DoctrineGateway
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias());
        return $query->execute([], HydrationType::HYDRATE_ARRAY);
    }

    public function findAllMaps(): array
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->innerJoin('s.Map m');
        return $query->execute([], HydrationType::HYDRATE_ARRAY);
    }

    /**
     * @throws NoResultsFoundException
     */
    public function findOneByUrl(string $url): array
    {
        $query = $this->createQuery();
        $query
            ->from($this->alias())
            ->where('s.url = :url')
        ;
        $state = $query->fetchOne([':url' => $url], HydrationType::HYDRATE_ARRAY);

        if ($state) {
            return $state;
        }

        throw new NoResultsFoundException("State with url '$url' cannot be found");
    }
}
