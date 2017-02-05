<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use Mandragora\Form\Crud\AbstractCrud;

/**
 * Form for adding/updating users
 */
class Detail extends AbstractCrud
{
    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('version');
        $this->removeElement('state');
    }

    /**
     * @return void
     */
    public function prepareForEditing()
    {
        $this->getElement('username')
             ->removeValidator('Db_Doctrine_NoRecordExists');
        $this->getElement('username')
             ->setAttrib('readonly', 'readonly')
             ->setAttrib('class', 'readonly');
    }

    /**
     * @param array $state
     * @return void
     */
    public function setState(array $state)
    {
        $haystack = array_keys($state);
        $category = $this->getElement('state');
        $category->getValidator('InArray')->setHaystack($haystack);
        $options = array('' => 'form.emptyOption') + $state;
        $category->setMultioptions($options);
    }

    /**
     * @param string $currentState
     * @return void
     */
    public function removeInvalidStates($currentState)
    {
        $state = $this->getElement('state');
        $options = $state->getMultioptions();
        if ((string)$currentState == 'unconfirmed') {
            unset($options['active']);
            $haystack = array_keys($options);
            unset($haystack[0]);
        } else {
            unset($options['unconfirmed']);
            $haystack = array_keys($options);
            unset($haystack[0]);

        }
        $state->getValidator('InArray')->setHaystack($haystack);
        $state->setMultioptions($options);
    }
}
