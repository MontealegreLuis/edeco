<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\RecommendedProperty;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->propertyForm);
        $this->assertCount(3, $this->propertyForm->getElements());
    }

    /** @test */
    function it_sets_the_value_of_the_property_id()
    {
        $propertyId = 3;

        $this->propertyForm->setPropertyId($propertyId);

        $this->assertEquals($propertyId, $this->propertyForm->getElement('id')->getValue());
    }

    /** @before */
    function createForm()
    {
        $this->propertyForm = FormFactory::useConfiguration()->create('Detail', 'RecommendedProperty');
    }

    /** @var Detail */
    private $propertyForm;
}
