<?php
/**
 * PHP version 5
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
        $this->getElement('id')->setValue((int)$propertyId);
    }

    /**
     * @param array $options
     * @return void
     */
    public function setStates(array $states)
    {
        $state = $this->getElement('state');
        $state->getValidator('InArray')->setHaystack(array_keys($states));
        $options = array('' => 'form.emptyOption') + $states;
        $state->setMultioptions($options);
    }

    /**
     * @param array $options
     * @return void
     */
    public function setCities(array $cities)
    {
        $city = $this->getElement('cityId');
        $city->getValidator('InArray')->setHaystack(array_keys($cities));
        $options = array('' => 'form.emptyOption') + $cities;
        $city->setMultioptions($options);
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
