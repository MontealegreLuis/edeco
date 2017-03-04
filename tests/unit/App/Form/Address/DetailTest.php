<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Address;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->addressForm);
        $this->assertCount(9, $this->addressForm->getElements());
    }

    /** @test */
    function it_prepares_the_form_for_the_create_action()
    {
        $this->addressForm->prepareForCreating();

        $this->assertCount(8, $this->addressForm->getElements());
        $this->assertNull($this->addressForm->getElement('version'));
    }

    /** @test */
    function it_gives_the_state_id_a_value()
    {
        $stateId = 10;

        $this->addressForm->setStateId($stateId);

        $this->assertEquals(
            $stateId,
            $this->addressForm->getElement('state')->getValue()
        );
    }

    /** @test */
    function it_configures_the_cities_select_element()
    {
        $cities = [
            ['id' => 1, 'name' => 'Puebla'],
            ['id' => 2, 'name' => 'Michoacan'],
            ['id' => 3, 'name' => 'Oaxaca'],
        ];

        $this->addressForm->setCities($cities);

        /** @var \Zend_Form_Element_Select $cityElement */
        $cityElement = $this->addressForm->getElement('cityId');
        $options = $cityElement->getMultiOptions();

        $this->assertArraySubset(
            [1 => 'Puebla', 2 => 'Michoacan', 3 => 'Oaxaca'],
            $options
        );
        $this->arrayHasKey('', $options);
        $this->assertArraySubset(
            [1 => 1, 2 => 2, 3 => 3],
            $cityElement->getValidator('InArray')->getHaystack()
        );
    }

    /** @test */
    function it_removes_the_city_validator()
    {
        $this->addressForm->removeCityValidator();

        $cityElement = $this->addressForm->getElement('cityId');

        $this->assertFalse($cityElement->getValidator('InArray'));
    }

    /** @test */
    function it_configures_the_states_select_element()
    {
        $states = [
            ['id' => 1, 'name' => 'Puebla'],
            ['id' => 2, 'name' => 'Michoacan'],
            ['id' => 3, 'name' => 'Oaxaca']
        ];

        $this->addressForm->setStates($states);

        $stateElement = $this->addressForm->getElement('state');
        $options = $stateElement->getMultioptions();
        $this->assertArraySubset(
            [1 => 'Puebla', 2 => 'Michoacan', 3 => 'Oaxaca'],
            $options
        );
        $this->arrayHasKey('', $options);
        $this->assertEquals(
            [1, 2, 3],
            $stateElement->getValidator('InArray')->getHaystack()
        );
    }

    /** @test */
    function it_sets_the_address_id_element_value()
    {
        $propertyId = 3;

        $this->addressForm->setIdValue($propertyId);

        $this->assertEquals(3,$this->addressForm->getElement('id')->getValue());
    }

    /** @test */
    function it_adds_default_no_city_option()
    {
        $this->addressForm->setNoCitiesOption();

        $options = $this->addressForm->getElement('cityId')->getMultiOptions();

        $this->assertArrayHasKey('', $options);
    }

    /** @before */
    function createForm()
    {
        $this->addressForm = FormFactory::useConfiguration()->create('Detail', 'Address');
    }

    /** @var Detail */
    private $addressForm;
}
