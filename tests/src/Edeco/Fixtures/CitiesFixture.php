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

class CitiesFixture
{
    /** @var array */
    private $rows;

    public static function fromDSN(string $dsn): CitiesFixture
    {
        return new CitiesFixture($dsn);
    }

    private function __construct(string $dsn)
    {
        $connection = new DBALConnection(DriverManager::getConnection(['url' => $dsn]));
        $connection->registerPlatformType('enum', 'string');
        $this->fixture = new Fixture($connection);
        $this->fixture->load(__DIR__ . '/../../../fixtures/cities.yml');
        $this->rows = $this->fixture->rows();
    }

    public function addMoreStates(): void
    {
        $this->fixture->load(__DIR__ . '/../../../fixtures/states.yml');
        $this->rows += $this->fixture->rows();
    }

    public function stateId(): int
    {
        return $this->state()['id'];
    }

    public function state():array
    {
        return $this->rows['state_1'];
    }

    public function stateUrl(): string
    {
        return $this->state()['url'];
    }

    public function cities(): array
    {
        return array_filter($this->rows, function ($key) {
            return 0 === strpos($key, 'city_');
        }, ARRAY_FILTER_USE_KEY);
    }

    public function states(): array
    {
        $states = array_filter($this->rows, function ($key) {
            return 0 === strpos($key, 'state_');
        }, ARRAY_FILTER_USE_KEY);

        return $states;
    }
}