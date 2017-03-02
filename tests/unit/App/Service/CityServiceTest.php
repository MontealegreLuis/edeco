<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\City;
use App\Service\City as CityService;
use PHPUnit_Framework_TestCase as TestCase;

class CityServiceTest extends TestCase
{
    /** @test */
    function it_can_create_a_city_model()
    {
        $this->assertInstanceOf(City::class, $this->cityService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->cityService = new CityService('City');
    }

    /** @var CityService */
    private $cityService;
}
