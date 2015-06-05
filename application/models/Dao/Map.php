<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_Map', 'doctrine');

/**
 * App_Model_Dao_Map
 *
 * @property integer $id
 * @property integer $top
 * @property integer $left
 * @property float $width
 * @property App_Model_State $State
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_Map extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('map');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('top', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('`left`', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('`width`', 'float', null, array(
             'type' => 'float',
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('App_Model_Dao_State as State', array(
             'local' => 'id',
             'foreign' => 'id'));
    }
}
