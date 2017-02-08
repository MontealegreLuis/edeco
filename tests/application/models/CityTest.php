<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\City;

/**
 * Unit tests for Edeco_Model_Address class
 */
class Edeco_Model_CityTest extends ControllerTestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'id' => 1, 'name' => 'Puebla', 'stateId' => 1
        ];
        $city = new City($values);
        $this->assertEquals('Puebla', (string)$city);
    }

    public function testCanAccessProperties()
    {
        $values = [
            'id' => 1, 'name' => 'Puebla', 'stateId' => 1
        ];
        $city = new City($values);
        $this->assertEquals(1, $city->id);
        $this->assertEquals('Puebla', $city->name);
        $this->assertEquals(1, $city->stateId);
    }
}
