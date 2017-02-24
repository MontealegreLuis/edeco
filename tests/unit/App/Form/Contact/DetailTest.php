<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Contact;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->contactForm);
        $this->assertCount(5, $this->contactForm->getElements());
    }

    /** @before */
    function createForm()
    {
        $this->contactForm = FormFactory::buildFromConfiguration()->create('Detail',
            'Contact');
    }

    /** @var Detail */
    private $contactForm;
}
