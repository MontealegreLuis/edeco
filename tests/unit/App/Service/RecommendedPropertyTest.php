<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\RecommendedProperty;
use App\Service\RecommendedProperty as RecommendedPropertyService;
use PHPUnit_Framework_TestCase as TestCase;

class RecommendedPropertyTest extends TestCase
{
    /** @test */
    function it_can_create_a_recommended_property_model()
    {
        $this->assertInstanceOf(RecommendedProperty::class, $this->propertyService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->propertyService = new RecommendedPropertyService('RecommendedProperty');
    }

    /** @var RecommendedPropertyService */
    private $propertyService;
}
