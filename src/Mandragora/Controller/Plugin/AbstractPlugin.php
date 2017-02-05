<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Plugin;

use Zend_Controller_Plugin_Abstract;
use Zend_Controller_Front;
use Zend_Controller_Action_Exception;
use Zend_Controller_Action_HelperBroker;

/**
 * Base class for controller plugins
 */
class AbstractPlugin extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Application_Bootstrap_Bootstrap
     */
    protected $bootstrap;

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $flash;

    /**
     * @param string $name
     * @return mixed
     */
    public function getResource($name)
    {
        if (is_null($this->bootstrap)) {
            $fc = Zend_Controller_Front::getInstance();
            $this->bootstrap = $fc->getParam('bootstrap');
        }
        if ($this->bootstrap->hasResource($name)) {
            return $this->bootstrap->getResource($name);
        } else if ($this->bootstrap->hasPluginResource($name)) {
            return $this->bootstrap->getPluginResource($name);
        } else {
            $message = "Resource '$name' is not registered.";
            throw new Zend_Controller_Action_Exception($message, 500);
        }
    }

    /**
     * @param $namespace = null
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function flash($namespace = null)
    {
        if (is_null($this->flash)) {
            $this->flash = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'FlashMessenger'
            );
        }
        return $this->flash;
    }
}
