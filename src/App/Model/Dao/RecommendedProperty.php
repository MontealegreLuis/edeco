<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('App_Model_Dao_RecomendedProperty', 'doctrine');

/**
 * App_Model_Dao_RecommendedProperty
 *
 * @property integer $propertyId
 * @property integer $similarPropertyId
 * @property App_Model_Property $Property
 * @property App_Model_Property $Property_2
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Model_Dao_RecommendedProperty extends Doctrine_Record
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
        $this->hasOne('App_Model_Dao_Property as Property', array(
             'local' => 'propertyId',
             'foreign' => 'id'));

        $this->hasOne('App_Model_Dao_Property as RecommendedProperty', array(
             'local' => 'recommendedPropertyId',
             'foreign' => 'id'));
    }
}
