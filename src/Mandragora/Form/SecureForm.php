<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Form;

use Zend_Form as Form;

/**
 * Base class for forms
 */
abstract class SecureForm extends Form
{
    /**
     * @return void
     * @throws \Zend_Form_Exception
     */
    public function addHash(array $options)
    {
        $hash = $this->createElement('hash', 'csrf');
        $decorators = [
            ['ViewScript', ['viewScript' => $options['viewScript']]]
        ];
        $hash->setSalt($options['saltValue'])
             ->setDecorators($decorators);
        $this->addElement($hash);
        $this->getDisplayGroup('data')->addElement($hash);
    }
}
