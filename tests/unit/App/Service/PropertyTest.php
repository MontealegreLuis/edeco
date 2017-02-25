<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Property;
use App\Service\Property as PropertyService;
use PHPUnit_Framework_TestCase as TestCase;

class PropertyTest extends TestCase
{
    /** @test */
    function it_creates_a_property_model()
    {
        $this->assertInstanceOf(Property::class, $this->propertyService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->propertyService = new PropertyService('Property');
    }

    /** @var PropertyService */
    private $propertyService;
}
