<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Contact;
use App\Service\Contact as ContactService;
use PHPUnit_Framework_TestCase as TestCase;

class ContactTest extends TestCase
{
    /** @test */
    function it_creates_a_contact_model()
    {
        $this->assertInstanceOf(Contact::class, $this->contactService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->contactService = new ContactService('Contact');
    }

    /** @var ContactService */
    private $contactService;
}
