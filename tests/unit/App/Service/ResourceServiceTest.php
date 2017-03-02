<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use PHPUnit_Framework_TestCase as TestCase;

class ResourceServiceTest extends TestCase
{
    /** @test */
    function it_does_not_create_a_resource_model()
    {
        $this->assertNull($this->resourceService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->resourceService = new Resource('Resource');
    }

    /** @var Resource */
    private $resourceService;
}
