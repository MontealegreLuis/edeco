<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_State', 'doctrine');

/**
 * App_Model_Dao_State
 *
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $City
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_State extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('state');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'length' => 45,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('url', 'string', 45, array(
                     'type' => 'string',
                     'length' => 45,
                     'fixed' => false,
                     'unsigned' => false,
                     'primary' => false,
                     'notnull' => true,
                     'autoincrement' => false,
        ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('App_Model_Dao_City as City', array(
             'local' => 'id',
             'foreign' => 'stateId'));
        $this->hasOne('App_Model_Dao_Map as Map', array(
                     'local' => 'id',
                     'foreign' => 'id'));
    }
}
