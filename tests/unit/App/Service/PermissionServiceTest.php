<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use PHPUnit_Framework_TestCase as TestCase;

class PermissionServiceTest extends TestCase
{
    /** @test */
    function it_does_not_create_a_model_for_permissions()
    {
        $this->assertNull($this->permissionService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->permissionService = new Permission('Permission');
    }

    /** @var Permission */
    private $permissionService;
}
