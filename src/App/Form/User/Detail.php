<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use Mandragora\Form\Crud\CrudForm;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;

/**
 * Form for adding/updating users
 */
class Detail extends CrudForm
{
    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('state');
    }

    /**
     * @return void
     * @throws \Zend_Form_Exception
     */
    public function prepareForEditing()
    {
        $this->getElement('username')->removeValidator(NoRecordExists::class);
        $this->getElement('username')
             ->setAttrib('readonly', 'readonly')
             ->setAttrib('class', 'readonly');
    }

    /**
     * @return void
     */
    public function setState(array $states)
    {
        /** @var \Zend_Form_Element_Select $state */
        $state = $this->getElement('state');
        $state->setMultiOptions(['' => 'form.emptyOption'] + $states);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $state->getValidator('InArray');
        $validator->setHaystack(array_keys($states));
    }

    /**
     * @return void
     */
    public function removeInvalidStates(string $currentState)
    {
        /** @var \Zend_Form_Element_Select $state */
        $state = $this->getElement('state');
        $options = $state->getMultiOptions();

        $haystack = $this->generateHaystackFromState($currentState, $options);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $state->getValidator('InArray');
        $validator->setHaystack($haystack);
        $state->setMultiOptions($options);
    }

    private function generateHaystackFromState(string $currentState, array &$options): array
    {
        if ($currentState === 'unconfirmed') {
            return $this->removeInvalid($options, 'active');
        }
        return $this->removeInvalid($options, 'unconfirmed');
    }

    private function removeInvalid(array &$options, string $state): array
    {
        unset($options[$state]);
        $haystack = array_keys($options);
        unset($haystack[0]);
        return $haystack;
    }
}
