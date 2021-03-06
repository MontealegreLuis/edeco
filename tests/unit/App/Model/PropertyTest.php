<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use PHPUnit_Framework_TestCase as TestCase;

class PropertyTest extends TestCase
{
    /** @var Property */
    protected $property;

    /**
     * @var array
     */
    protected $propertyInformation;

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->property = new Property();
    }

    public function testCanCreateAddressModelFromArray()
    {
        $address = [
            'streetAndNumber' => '13 sur 10932',
            'neighborhood' => 'Eclipse',
            'zipCode' => '',
            'City' => ['name' => 'Puebla', 'State' => ['name' => 'Puebla']]
        ];
        $this->property->Address = $address;
        $this->assertInstanceOf(
            Address::class,
            $this->property->Address,
            '$this->address should be an instance of Address'
        );
        $this->assertEquals(
            '13 sur 10932', $this->property->Address->streetAndNumber
        );
        $this->assertEquals('Eclipse', $this->property->Address->neighborhood);
        $this->assertNull($this->property->Address->zipCode);
        $this->assertInstanceOf(City::class, $this->property->Address->City);
        $this->assertInstanceOf(State::class, $this->property->Address->City->State);
    }

    public function testCanAccessNewProperties()
    {
        $this->property = $this->createProperty();
        $this->assertEquals(
            '12.5 m²',
            $this->property->totalSurface->render()
        );
        $this->assertEquals(
            '16.5 m²',
            $this->property->metersOffered->render()
        );
        $this->assertEquals(
            '20.5 m',
            $this->property->metersFront->render()
        );
        $this->assertEquals(
            $this->property->landUse,
            $this->propertyInformation['landUse']
        );
        $this->assertEquals(
            $this->property->creationDate,
            $this->propertyInformation['creationDate']
        );
    }

    protected function createProperty(): Property
    {
        $this->propertyInformation = [
            'id' => null, 'name' => 'Local Plaza Dorada',
            'url' => 'local-plaza-dorada', 'description' => 'Local amplio',
            'price' => '5000 al mes',
            'address' => '13 sur 10932|Eclipse||Puebla|Puebla|México',
            'addressReference' => 'Junto a Suburbia',
            'latitude' => 100.1, 'longitude' => 120.2, 'category' => 'premises',
            'totalSurface' => 12.5, 'metersOffered' => 16.5,
            'metersFront' => 20.5, 'landUse' => 'commercial',
            'creationDate' => '2010-07-07', 'active' => 1,
            'Picture' => []
        ];
        return new Property($this->propertyInformation);
    }
}
