<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\UserDao;
use App\Model\Gateway\User as UserGateway;
use App\Model\User;
use ControllerTestCase;
use Doctrine_Core as Doctrine;
use Edeco\Fixtures\UsersFixture;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class UserTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
	public function it_can_save_a_user()
	{
        $user = new User([
            'password' => 'changeme',
		    'username' => 'lemuel',
		    'state' => 'active',
		    'roleName' => 'admin',
            'creationDate' => '2017-02-28',
        ]);

        $this->userGateway->insert($user);

        $savedUser = $this->userTable->findOneByUsername($user->username);

        $this->assertEquals($user->username, $savedUser->username);
        $this->assertEquals($user->password, $savedUser->password);
        $this->assertEquals($user->state, $savedUser->state);
        $this->assertEquals($user->roleName, $savedUser->roleName);
    }

    /** @before */
	function loadFixture(): void
    {
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = UsersFixture::fromDSN($manager->getConfiguration()['dsn']);
        $this->userTable = Doctrine::getTable(UserDao::class);
        $this->userGateway = new UserGateway(new UserDao());
    }

    /** @var \Doctrine_Table */
    private $userTable;

    /** @var UserGateway */
    private $userGateway;

    /** @var UsersFixture */
    private $fixture;
}
