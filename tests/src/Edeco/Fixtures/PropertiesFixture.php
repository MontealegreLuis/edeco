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
    /** @var Fixture */
    private $fixture;

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
        $this->fixture = new Fixture($connection);
        $this->fixture->load(__DIR__ . '/../../../fixtures/properties.yml');
        $this->rows = $this->fixture->rows();
    }

    public function addMoreProperties(): void
    {
        $this->fixture->load(__DIR__ . '/../../../fixtures/more-properties.yml');
        $this->rows += $this->fixture->rows();
    }

    public function includeSecurityRows(): void
    {
        $this->fixture->load(__DIR__ . '/../../../fixtures/security.yml');
        $this->rows += $this->fixture->rows();
    }

    public function adminUser(): array
    {
        return $this->rows['user_1'];
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

    public function categoryId(): int
    {
        return $this->rows['category_1']['id'];
    }

    public function propertyId(): int
    {
        return $this->property()['id'];
    }

    public function property(): array
    {
        return $this->rows['property_1'];
    }

    public function properties(): array
    {
        return array_filter($this->rows, function (array $key) {
            return strpos($key, 'property_') === 0;
        }, ARRAY_FILTER_USE_KEY);
    }
}
