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
 * Gateway for Address model objects
 */
class AddressGateway extends DoctrineGateway
{
    /**
     * @throws NoResultsFoundException
     */
    public function findOneById(int $id): array
    {
        $query = $this->createQuery();
        $query->from($this->alias())
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->where('a.id = :id');

        $address = $query->fetchOne([':id' => $id], HydrationType::HYDRATE_ARRAY);

        if ($address) {
            return $address;
        }

        throw new NoResultsFoundException("Address with id '$id' cannot be found");
    }

    public function saveGeoPosition(int $id, array $geoPosition): void
    {
        $query = $this->createQuery();
        $query->update($this->alias())
              ->set('latitude', ':latitude')
              ->set('longitude', ':longitude')
              ->where('id = :id');
        $query->execute([
            ':latitude' => $geoPosition['latitude'],
            ':longitude' => $geoPosition['longitude'],
            ':id' => $id
        ]);
    }
}
