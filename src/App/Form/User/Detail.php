<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use Mandragora\Form\Crud\AbstractCrud;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;

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
     * @throws \Zend_Form_Exception
     */
    public function prepareForEditing()
    {
        $this->getElement('username')
             ->removeValidator(NoRecordExists::class);
        $this->getElement('username')
             ->setAttrib('readonly', 'readonly')
             ->setAttrib('class', 'readonly');
    }

    /**
     * @return void
     */
    public function setState(array $states)
    {
        $category = $this->getElement('state');
        $category->getValidator('InArray')->setHaystack(array_keys($states));
        $category->setMultioptions(['' => 'form.emptyOption'] + $states);
    }

    /**
     * @return void
     */
    public function removeInvalidStates(string $currentState)
    {
        $state = $this->getElement('state');
        $options = $state->getMultioptions();
        if ($currentState == 'unconfirmed') {
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
