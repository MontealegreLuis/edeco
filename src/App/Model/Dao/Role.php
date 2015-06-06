<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_Role', 'doctrine');

/**
 * App_Model_Dao_Role
 * 
 * @property string $name
 * @property string $parentRole
 * @property Doctrine_Collection $User
 * 
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_Role extends Doctrine_Record
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
        $this->hasMany('App_Model_Dao_User as User', array(
             'local' => 'name',
             'foreign' => 'roleName'));
    }
}