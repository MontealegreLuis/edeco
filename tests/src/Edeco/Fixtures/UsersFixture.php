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

class UsersFixture
{
    /** @var Fixture */
    private $fixture;

    /** @var array */
    private $rows;

    public static function fromDSN(string $dsn): UsersFixture
    {
        return new UsersFixture($dsn);
    }

    private function __construct(string $dsn)
    {
        $connection = new DBALConnection(DriverManager::getConnection(['url' => $dsn]));
        $connection->registerPlatformType('enum', 'string');
        $fixture = new Fixture($connection);
        $fixture->load(__DIR__ . '/../../../fixtures/security.yml');
        $this->rows = $fixture->rows();
    }

    public function adminRoleName(): string
    {
        return $this->rows['role_1']['name'];
    }
}