<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Exception;

/**
 * Handle PHP errors as exceptions
 */
class ErrorToException extends Exception
{
    /**
     * @param int $errorNumber
     * @param string $errorMessage
     */
    public static function handleError($errorNumber, $errorMessage)
    {
        throw new self($errorMessage, $errorNumber);
    }

    /**
     * @return void
     */
    public static function register()
    {
        set_error_handler(array(__CLASS__, 'handleError'));
    }

}