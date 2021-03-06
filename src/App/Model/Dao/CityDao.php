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
Doctrine_Manager::getInstance()->bindComponent(CityDao::class, 'doctrine');

/**
 * @property integer $id
 * @property string $name
 * @property integer $stateId
 * @property App_Model_State $State
 * @property Doctrine_Collection $Address
 */
class CityDao extends Doctrine_Record
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
        $this->hasOne('App\Model\Dao\StateDao as State', [
             'local' => 'stateId',
             'foreign' => 'id'
        ]);

        $this->hasMany('App\Model\Dao\AddressDao as Address', [
             'local' => 'id',
             'foreign' => 'cityId'
        ]);
    }
}
