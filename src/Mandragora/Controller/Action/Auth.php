<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Action;

use Zend_Controller_Action_Exception;

/**
 * Base class for Mandragora controllers that handle authentication process
 */
class Auth extends AbstractAction
{
    /**
     * @var array
     */
    protected $sessionOptions;

    /**
     * @return mixed
     * @throws Zend_Controller_Action_Exception
     */
    public function getSessionOption($key)
    {
        if (is_null($this->sessionOptions)) {
            $this->sessionOptions = $this->getInvokeArg('bootstrap')
                                         ->getPluginResource('session')
                                         ->getOptions();
        }
        if (!array_key_exists($key, $this->sessionOptions)) {
            $message = "Session option '$key' does not exists";
            throw new Zend_Controller_Action_Exception($message, 500);
        }
        return $this->sessionOptions[$key];
    }
}
