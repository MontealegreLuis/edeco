<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_User', 'doctrine');

/**
 * App_Model_Dao_User
 * 
 * @property string $username
 * @property string $password
 * @property enum $state
 * @property string $roleName
 * @property string $confirmationKey
 * @property date $creationDate
 * @property App_Model_Role $Role
 * 
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_User extends Doctrine_Record
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
        $this->hasOne('App_Model_Dao_Role as Role', array(
             'local' => 'roleName',
             'foreign' => 'name'));
    }
}
