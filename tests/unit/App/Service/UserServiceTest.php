<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\User;
use App\Service\User as UserService;
use PHPUnit_Framework_TestCase as TestCase;

class UserServiceTest extends TestCase
{
    /** @test */
    function it_creates_a_user_model()
    {
        $this->assertInstanceOf(User::class, $this->userService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->userService = new UserService('Service');
    }

    /** @var UserService */
    private $userService;
}
