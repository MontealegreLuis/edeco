<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\GoogleMap;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->googleForm);
        $this->assertCount(5, $this->googleForm->getElements());
    }

    /** @test */
    function it_initialize_the_address_id_value()
    {
        $addressId = 4;

        $this->googleForm->setAddressId($addressId);

        $this->assertEquals($addressId, $this->googleForm->getElement('addressId')->getValue());
    }

    /** @test */
    function it_removes_the_version_element_before_adding_a_new_map()
    {
        $this->googleForm->prepareForCreating();

        $elements = $this->googleForm->getElements();
        $this->assertCount(4, $elements);
        $this->assertArrayNotHasKey('version', $elements);
    }

    /** @before */
    function createForm()
    {
        $this->googleForm = FormFactory::buildFromConfiguration()->create("Detail", "GoogleMap");
    }

    /** @var Detail */
    private $googleForm;
}
