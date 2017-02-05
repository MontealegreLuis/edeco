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
Doctrine_Manager::getInstance()->bindComponent(StateDao::class, 'doctrine');

/**
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $City
 */
class StateDao extends Doctrine_Record
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
        $this->hasMany('App\Model\Dao\CityDao as City', [
             'local' => 'id',
             'foreign' => 'stateId'
        ]);
        $this->hasOne('App\Model\Dao\Map as Map', [
             'local' => 'id',
             'foreign' => 'id'
        ]);
    }
}
