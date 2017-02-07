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
Doctrine_Manager::getInstance()->bindComponent(RecommendedPropertyDao::class, 'doctrine');

/**
 * @property integer $propertyId
 * @property integer $similarPropertyId
 * @property App_Model_Property $Property
 * @property App_Model_Property $Property_2
 */
class RecommendedPropertyDao extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('recommended_property');
        $this->hasColumn('propertyId', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('recommendedPropertyId', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('App\Model\Dao\PropertyDao as Property', [
             'local' => 'propertyId',
             'foreign' => 'id'
        ]);

        $this->hasOne('App\Model\Dao\PropertyDao as RecommendedProperty', [
             'local' => 'recommendedPropertyId',
             'foreign' => 'id'
        ]);
    }
}
