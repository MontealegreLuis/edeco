<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use PHPUnit_Framework_TestCase as TestCase;

class RoleServiceTest extends TestCase
{
    /** @test */
    function it_does_not_create_a_role_model()
    {
        $this->assertNull($this->roleService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->roleService = new Role('Role');
    }

    /** @var Role */
    private $roleService;
}
