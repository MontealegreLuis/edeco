<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_Picture', 'doctrine');

/**
 * App_Model_Dao_Picture
 * 
 * @property integer $id
 * @property string $shortDescription
 * @property string $filename
 * @property integer $propertyId
 * @property integer $version
 * 
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_Picture extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('picture');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('shortDescription', 'string', 80, array(
             'type' => 'string',
             'length' => 80,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('filename', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('propertyId', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
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
