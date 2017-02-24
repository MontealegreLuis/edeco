<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use App\Enum\UserState;
use Mandragora\FormFactory;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->userForm);
        $this->assertCount(3, $this->userForm->getElements());
    }

    /** @test */
    function it_gets_ready_to_add_a_new_user()
    {
        $this->userForm->prepareForCreating();

        $elements = $this->userForm->getElements();
        $this->assertCount(2, $elements);
        $this->assertArrayNotHasKey('version', $elements);
        $this->assertArrayNotHasKey('state', $elements);
    }

    /** @test */
    function it_gets_ready_to_update_a_user()
    {
        $this->userForm->prepareForEditing();

        $username = $this->userForm->getElement('username');

        $this->assertFalse($username->getValidator(NoRecordExists::class));
        $this->assertArrayHasKey('readonly', $username->getAttribs());
        $this->assertArrayHasKey('class', $username->getAttribs());
    }

    /** @test */
    function it_configures_the_states_element()
    {
        $this->userForm->setState(UserState::values());

        /** @var \Zend_Form_Element_Select $state */
        $state = $this->userForm->getElement('state');

        $this->assertArraySubset(UserState::values(), $state->getMultiOptions());

        /** @var \Zend_Validate_InArray $validator */
        $validator = $state->getValidator('InArray');

        $this->assertEquals(
            [
                UserState::Active,
                UserState::Unconfirmed,
                UserState::Inactive,
                UserState::Banned,
            ],
            $validator->getHaystack()
        );
    }

    /** @test */
    function it_configures_element_if_current_state_is_unconfirmed()
    {
        $this->userForm->removeInvalidStates(UserState::Unconfirmed);

        /** @var \Zend_Form_Element_Select $state */
        $state = $this->userForm->getElement('state');
        $this->assertArrayNotHasKey('active', $state->getMultiOptions());

        /** @var \Zend_Validate_InArray $validator */
        $validator = $state->getValidator('InArray');
        $this->assertArrayNotHasKey('active', $validator->getHaystack());
    }

    /** @before */
    function createForm()
    {
        $this->userForm = (new FormFactory(true))->create('Detail', 'User');
    }

    /** @var Detail */
    private $userForm;
}
