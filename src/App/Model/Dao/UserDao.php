<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Dao;

use Doctrine_Manager;
use Doctrine_Record;

// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent(UserDao::class, 'doctrine');

/**
 * @property string $username
 * @property string $password
 * @property enum $state
 * @property string $roleName
 * @property string $confirmationKey
 * @property date $creationDate
 * @property App_Model_Role $Role
 */
class UserDao extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('username', 'string', 120, array(
             'type' => 'string',
             'length' => 120,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('password', 'string', 64, array(
             'type' => 'string',
             'length' => 64,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('state', 'enum', 11, array(
             'type' => 'enum',
             'length' => 11,
             'fixed' => false,
             'unsigned' => false,
             'values' =>
             array(
              0 => 'active',
              1 => 'unconfirmed',
              2 => 'inactive',
              3 => 'banned',
             ),
             'primary' => false,
             'default' => 'active',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('roleName', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('confirmationKey', 'string', 64, array(
             'type' => 'string',
             'length' => 64,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('creationDate', 'date', null, array(
             'type' => 'date',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('App\Model\Dao\RoleDao as Role', [
             'local' => 'roleName',
             'foreign' => 'name'
        ]);
    }
}
