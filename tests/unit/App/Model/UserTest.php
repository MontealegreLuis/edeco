<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use PHPUnit_Framework_TestCase as TestCase;

class UserTest extends TestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'username' => 'luis@test.com', 'password' => 'ilovemyj0b',
            'state' => 'active', 'roleName' => 'admin', 'confirmationKey' => null,
            'creationDate' => '2010-05-25',
        ];
        $user = new User($values);
        $this->assertEquals('luis@test.com (active)', (string) $user);
    }

    public function testCanAccessProperties()
    {
        $values = [
            'username' => 'luis@test.com', 'password' => 'ilovemyj0b',
            'state' => 'active', 'roleName' => 'admin', 'confirmationKey' => null,
            'creationDate' => '2010-05-25',
        ];
        $user = new User($values);
        $this->assertEquals('luis@test.com', $user->username);
        $this->assertEquals(sha1($values['password']), $user->password);
        $this->assertEquals('active', $user->state);
        $this->assertEquals($values['roleName'], $user->roleName);
        $this->assertEquals($values['confirmationKey'], $user->confirmationKey);
        $this->assertEquals($values['creationDate'], $user->creationDate);
    }
}
