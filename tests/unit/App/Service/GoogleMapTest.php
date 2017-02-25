<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use PHPUnit_Framework_TestCase as TestCase;

class GoogleMapTest extends TestCase
{
    /** @test */
    function it_does_not_create_a_model_for_maps()
    {
        $this->assertNull($this->googleMapService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->googleMapService = new GoogleMap('GoogleMap');
    }

    /** @var GoogleMap */
    private $googleMapService;
}
