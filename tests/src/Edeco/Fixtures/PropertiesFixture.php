<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Fixtures;

use ComPHPPuebla\Fixtures\Connections\DBALConnection;
use ComPHPPuebla\Fixtures\Fixture;
use Doctrine\DBAL\DriverManager;

class PropertiesFixture
{
    /** @var array */
    private $rows;

    public static function fromDSN(string $dsn): PropertiesFixture
    {
        return new PropertiesFixture($dsn);
    }

    private function __construct(string $dsn)
    {
        $connection = new DBALConnection(DriverManager::getConnection(['url' => $dsn]));
        $connection->registerPlatformType('enum', 'string');
        $fixture = new Fixture($connection);
        $fixture->load(__DIR__ . '/../../../fixtures/properties.yml');
        $this->rows = $fixture->rows();
    }

    public function stateId(): int
    {
        return $this->rows['state_1']['id'];
    }

    public function cityId(): int
    {
        return $this->rows['city_1']['id'];
    }

    public function address(): array
    {
        return $this->rows['address_1'];
    }

    public function addressId(): int
    {
        return $this->address()['id'];
    }
}
