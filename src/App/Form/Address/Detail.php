<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Address;

use Mandragora\Form\Crud\AbstractCrud;

/**
 * Address form
 */
class Detail extends AbstractCrud
{
    public function setIdValue(int $propertyId)
    {
        $this->getElement('id')->setValue($propertyId);
    }

    /**
     * @return void
     */
    public function setStates(array $states)
    {
        $state = $this->getElement('state');
        $state->getValidator('InArray')->setHaystack(array_keys($states));
        $options = ['' => 'form.emptyOption'] + $states;
        $state->setMultioptions($options);
    }

    /**
     * @return void
     */
    public function setCities(array $cities)
    {
        $city = $this->getElement('cityId');
        $city->getValidator('InArray')->setHaystack(array_keys($cities));
        $options = ['' => 'form.emptyOption'] + $cities;
        $city->setMultioptions($options);
    }

    public function setStateId(int $stateId)
    {
        $this->getElement('state')->setValue($stateId);
    }

    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('version');
    }

    /**
     * @return void
     */
    public function prepareForEditing()
    {
    }
}
