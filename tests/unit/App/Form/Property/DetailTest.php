<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Property;

use App\Enum\PropertyAvailability;
use App\Enum\PropertyLandUse;
use Mandragora\FormFactory;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->propertyForm);
        $this->assertCount(16, $this->propertyForm->getElements());
    }

    /** @test */
    function it_gets_prepared_to_add_a_new_property()
    {
        $this->propertyForm->prepareForCreating();

        $elements = $this->propertyForm->getElements();
        $this->assertCount(14, $elements);
        $this->assertArrayNotHasKey('id', $elements);
        $this->assertArrayNotHasKey('version', $elements);
    }

    /** @test */
    function it_gets_prepared_to_edit_a_property()
    {
        $this->propertyForm->prepareForEditing();

        $this->assertCount(16, $this->propertyForm->getElements());
        $this->assertFalse($this->propertyForm->getElement('name')->getValidator(NoRecordExists::class));
    }

    /** @test */
    function it_configures_the_categories_element()
    {
        $categories = [
            ['id' => 1, 'name' => 'Terrenos'],
            ['id' => 2, 'name' => 'Bodegas'],
            ['id' => 3, 'name' => 'Oficinas']
        ];

        $this->propertyForm->setCategories($categories);

        /** @var \Zend_Form_Element_Select $category */
        $category = $this->propertyForm->getElement('categoryId');

        $this->assertArraySubset(
            [1 => 'Terrenos', 2 => 'Bodegas', 3 => 'Oficinas'],
            $category->getMultiOptions()
        );

        /** @var \Zend_Validate_InArray $validator */
        $validator = $category->getValidator('InArray');

        $this->assertEquals([1, 2, 3], $validator->getHaystack());
    }

    /** @test */
    function it_sets_the_availability_types_for_the_property()
    {
        $availabilities = PropertyAvailability::values();

        $this->propertyForm->setAvailabilities($availabilities);

        /** @var \Zend_Form_Element_Select $availability */
        $availability = $this->propertyForm->getElement('availabilityFor');
        $this->assertEquals($availabilities, $availability->getMultiOptions());

        /** @var \Zend_Validate_InArray $validator */
        $validator = $availability->getValidator('InArray');
        $this->assertEquals(
            [PropertyAvailability::Rent, PropertyAvailability::Sale],
            $validator->getHaystack()
        );
    }

    /** @test */
    function it_sets_the_land_use_options_for_the_property()
    {
        $uses = PropertyLandUse::values();

        $this->propertyForm->setLandUses($uses);

        /** @var \Zend_Form_Element_Select $landUse */
        $landUse = $this->propertyForm->getElement('landUse');
        $this->assertArraySubset($uses, $landUse->getMultiOptions());

        /** @var \Zend_Validate_InArray $validator */
        $validator = $landUse->getValidator('InArray');
        $this->assertEquals(
            [
                PropertyLandUse::Housing,
                PropertyLandUse::Commercial,
                PropertyLandUse::Industrial,
                PropertyLandUse::Mixed,
            ],
            $validator->getHaystack()
        );
    }

    /** @before */
    function createForm()
    {
        $this->propertyForm = FormFactory::buildFromConfiguration()->create('Detail', 'Property');
    }

    /** @var Detail */
    private $propertyForm;
}
