<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_Project', 'doctrine');

/**
 * App_Model_Dao_Project
 * 
 * @property integer $id
 * @property string $name
 * @property string $attachment
 * @property integer $version
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_Project extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('project');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 120, array(
             'type' => 'string',
             'length' => 120,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('attachment', 'string', 120, array(
             'type' => 'string',
             'length' => 120,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('version', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
    }
}
