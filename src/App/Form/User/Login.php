<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\User;

use Mandragora\Form\SecureForm;

/**
 * User's login form
 */
class Login extends SecureForm
{
    /**
     * @return void
     */
    public function setAuthenticationErrors(array $errorMessages)
    {
        foreach ($errorMessages as $name => $message) {
            $this->getElement($name)->setErrors([$message]);
        }
    }
}
