<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use Mandragora\Form\AbstractForm;

/**
 * User's login form
 */
class Login extends AbstractForm
{
    /**
     * @param array $errorMessages
     * @return void
     */
    public function setAuthenticationErrors(array $errorMessages)
    {
        foreach ($errorMessages as $name => $message) {
            $this->getElement($name)->setErrors(array($message));
        }
    }
}
