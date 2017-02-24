<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class LoginTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Login::class, $this->loginForm);
        $this->assertCount(3, $this->loginForm->getElements());
    }

    /** @test */
    function it_sets_the_authentication_error_messages()
    {
        $message = 'this value is required';
        $errorMessages = ['username' => $message, 'password' => $message];

        $this->loginForm->setAuthenticationErrors($errorMessages);

        $username = $this->loginForm->getElement('username');
        $password = $this->loginForm->getElement('password');

        $this->assertEquals([$message], $username->getErrorMessages());
        $this->assertEquals([$message], $password->getErrorMessages());
    }

    /** @before */
    function createForm()
    {
        $this->loginForm = (new FormFactory(true))->create('Login', 'User');
    }

    /** @var Login */
    private $loginForm;
}
