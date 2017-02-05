<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Form;

use Zend_Form;

/**
 * Base class for forms
 */
abstract class AbstractForm extends Zend_Form
{
    /**
     * @param array $options
     * @return void
     */
    public function addHash(array $options)
    {
        $hash = $this->createElement('hash', 'csrf');
        $decorators = array(
            array('ViewScript', array('viewScript' => $options['viewScript']))
        );
        $hash->setSalt($options['saltValue'])
             ->setDecorators($decorators);
        $this->addElement($hash);
        $this->getDisplayGroup('data')->addElement($hash);
    }
}
