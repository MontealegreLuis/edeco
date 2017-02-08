<?php
/**
 * PHP version 5.6
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
    /**
     * @param int $propertyId
     */
    public function setIdValue($propertyId)
    {
        $this->getElement('id')->setValue((int) $propertyId);
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

    /**
     * @param int $stateId
     */
    public function setStateId($stateId)
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
