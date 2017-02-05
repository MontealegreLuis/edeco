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
Doctrine_Manager::getInstance()->bindComponent(Role::class, 'doctrine');

/**
 * @property string $name
 * @property string $parentRole
 * @property Doctrine_Collection $User
 */
class Role extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('role');
        $this->hasColumn('name', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('parentRole', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => false,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('App\Model\Dao\User as User', [
             'local' => 'name',
             'foreign' => 'roleName'
        ]);
    }
}
