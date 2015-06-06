<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_Resource', 'doctrine');

/**
 * App_Model_Dao_Resource
 *
 * @property string $name
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_Resource extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('resource');
        $this->hasColumn('name', 'string', 25, array(
             'type' => 'string',
             'length' => 25,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
    }
}
