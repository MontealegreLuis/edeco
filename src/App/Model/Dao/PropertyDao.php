<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Dao;

use Doctrine_Manager;
use Doctrine_Record;

// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent(PropertyDao::class, 'doctrine');

/**
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $description
 * @property string $price
 * @property float $totalSurface
 * @property float $metersOffered
 * @property float $metersFront
 * @property enum $landUse
 * @property date $creationDate
 * @property enum $availabilityFor
 * @property integer $showOnWeb
 * @property string $contactName
 * @property string $contactPhone
 * @property string $contactCellphone
 * @property integer $categoryId
 * @property integer $version
 * @property Doctrine_Collection $Address
 * @property Doctrine_Collection $RecomendedProperty
 * @property Doctrine_Collection $RecomendedProperty_2
 */
class PropertyDao extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('property');
        $this->hasColumn('id', 'integer', 4, [
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
        ]);
        $this->hasColumn('name', 'string', 45, [
             'type' => 'string',
             'length' => 45,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('url', 'string', 55, [
             'type' => 'string',
             'length' => 55,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('description', 'string', null, [
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('price', 'string', null, [
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
        ]);
        $this->hasColumn('totalSurface', 'float', null, [
             'type' => 'float',
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('metersOffered', 'float', null, [
             'type' => 'float',
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('metersFront', 'float', null, [
             'type' => 'float',
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('landUse', 'enum', 10, [
             'type' => 'enum',
             'length' => 10,
             'fixed' => false,
             'unsigned' => false,
             'values' =>
             [
              0 => 'housing',
              1 => 'commercial',
              2 => 'industrial',
              3 => 'mixed',
             ],
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('creationDate', 'date', null, [
             'type' => 'date',
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('availabilityFor', 'enum', 4, [
             'type' => 'enum',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'values' =>
             [
              0 => 'rent',
              1 => 'sale',
             ],
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('showOnWeb', 'integer', 1, [
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => 0,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('contactName', 'string', 100, [
             'type' => 'string',
             'length' => 100,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
        ]);
        $this->hasColumn('contactPhone', 'string', 10, [
             'type' => 'string',
             'length' => 10,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
        ]);
        $this->hasColumn('contactCellphone', 'string', 13, [
             'type' => 'string',
             'length' => 13,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
        ]);
        $this->hasColumn('categoryId', 'integer', 4, [
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
        ]);
        $this->hasColumn('version', 'integer', 8, [
             'type' => 'integer',
             'length' => 8,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
        ]);
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('App\Model\Dao\AddressDao as Address', [
             'local' => 'id',
             'foreign' => 'id'
        ]);
        $this->hasMany('App\Model\Dao\RecommendedPropertyDao as Property', [
             'local' => 'id',
             'foreign' => 'propertyId'
        ]);
        $this->hasMany('App\Model\Dao\RecommendedPropertyDao as RecommendedProperty', [
             'local' => 'id',
             'foreign' => 'propertyId'
        ]);
        //This is no generated by Doctrine
        $this->hasMany('App\Model\Dao\PictureDao as Picture', [
             'local' => 'id',
             'foreign' => 'propertyId'
        ]);
        $this->hasOne('App\Model\Dao\CategoryDao as Category', [
             'local' => 'categoryId',
             'foreign' => 'id'
        ]);
    }
}
