<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Address;

use Mandragora\Form\Crud\CrudForm;

/**
 * Address form
 */
class Detail extends CrudForm
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
        $options = [];
        foreach ($states as $state) {
            $options[$state['id']] = $state['name'];
        }

        /** @var \Zend_Form_Element_Select $state */
        $state = $this->getElement('state');
        $state->setMultiOptions(['' => 'form.emptyOption'] + $options);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $state->getValidator('InArray');
        $validator->setHaystack(array_keys($options));
    }

    /**
     * @return void
     */
    public function setCities(array $cities)
    {
        $options = ['' => 'form.emptyOption'];
        foreach ($cities as $city) {
            $options[$city['id']] = $city['name'];
        }

        /** @var \Zend_Form_Element_Select $city */
        $city = $this->getElement('cityId');
        $city->setMultiOptions($options);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $city->getValidator('InArray');
        $validator->setHaystack(array_keys($options));
    }

    public function removeCityValidator(): void
    {
        $this->getElement('cityId')->removeValidator('InArray');
    }

    public function setNoCitiesOption()
    {
        /** @var \Zend_Form_Element_Select $city */
        $city = $this->getElement('cityId');
        $city->setMultiOptions(['' => 'form.emptyOption']);
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
}
