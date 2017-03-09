<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\RoleDao;
use App\Model\Dao\UserDao;
use App\Model\Gateway\User as UserGateway;
use App\Model\User;
use ControllerTestCase;
use Doctrine_Core as Doctrine;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class UserTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
	public function it_can_save_a_user()
	{
	    $this->insertRole();
        $user = new User([
            'password' => 'changeme',
		    'username' => 'lemuel',
		    'state' => 'active',
		    'roleName' => 'admin',
            'creationDate' => '2017-02-28',
        ]);

		$userGateway = new UserGateway(new UserDao());
        $userGateway->insert($user);

        $userTable = Doctrine::getTable(UserDao::class);
        $savedUser = $userTable->findOneByUsername($user->username);

        $this->assertEquals($user->username, $savedUser->username);
        $this->assertEquals($user->password, $savedUser->password);
        $this->assertEquals($user->state, $savedUser->state);
        $this->assertEquals($user->roleName, $savedUser->roleName);
	}

	private function insertRole(): void
    {
        $daoRole = new RoleDao();
        $daoRole->name = 'admin';
        $daoRole->save();
    }
}
