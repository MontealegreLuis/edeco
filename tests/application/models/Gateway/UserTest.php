<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Dao\RoleDao;
use App\Model\Dao\UserDao;
use App\Model\User;
use App\Model\Gateway\User as UserGateway;
use Doctrine_Core as Doctrine;
use Mandragora\Model\Property\PropertyInterface;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

/**
 * Unit tests for Edeco_Model_Gateway_User class
 */
class Edeco_Model_Gateway_UserTest extends ControllerTestCase implements DoctrineTestInterface
{
	public function testCanCreateUser()
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

	/**
	 * @return void
	 */
	private function insertRole(): void
    {
        $daoRole = new RoleDao();
        $daoRole->name = 'admin';
        $daoRole->save();
    }
}
