<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use PHPUnit_Framework_TestCase as TestCase;

class CityTest extends TestCase
{
    public function testCanConvertToString()
    {
        $city = new City(['id' => 1, 'name' => 'Puebla', 'stateId' => 1]);
        $this->assertEquals('Puebla, México', (string) $city);
    }

    public function testCanAccessProperties()
    {
        $city = new City(['id' => 1, 'name' => 'Puebla', 'stateId' => 1]);
        $this->assertEquals(1, $city->id);
        $this->assertEquals('Puebla', $city->name);
        $this->assertEquals(1, $city->stateId);
    }
}
