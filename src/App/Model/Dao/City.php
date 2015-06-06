<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_City', 'doctrine');

/**
 * App_Model_Dao_City
 *
 * @property integer $id
 * @property string $name
 * @property integer $stateId
 * @property App_Model_State $State
 * @property Doctrine_Collection $Address
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_City extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('city');
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
        $this->hasColumn('stateId', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
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
        $this->hasOne('App_Model_Dao_State as State', array(
             'local' => 'stateId',
             'foreign' => 'id'));

        $this->hasMany('App_Model_Dao_Address as Address', array(
             'local' => 'id',
             'foreign' => 'cityId'));
    }
}