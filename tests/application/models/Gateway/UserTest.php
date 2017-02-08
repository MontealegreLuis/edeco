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
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

/**
 * Unit tests for Edeco_Model_Gateway_User class
 */
class Edeco_Model_Gateway_UserTest extends ControllerTestCase implements DoctrineTestInterface
{
	public function testCanCreateUser()
	{
	    $this->insertRole();
        $user = new User();
		$user->password = 'changeme';
		$user->username = 'lemuel';
		$user->state = 'active';
		$user->roleName = 'admin';
		$userGateway = new UserGateway(new UserDao());
        $userGateway->insert($user);
        $userTable = Doctrine::getTable(UserDao::class);
		$this->assertEquals(
            $user->toArray(),
            $userTable->findOneByUsername($user->username)->toArray()
        );
	}

	/**
	 * @return void
	 */
	protected function insertRole()
    {
        $daoRole = new RoleDao();
        $daoRole->name = 'admin';
        $daoRole->save();
    }
}
