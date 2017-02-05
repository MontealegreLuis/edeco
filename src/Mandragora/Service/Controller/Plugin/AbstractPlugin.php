<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Controller\Plugin;

use Zend_Controller_Front;
use Zend_Controller_Action_Exception;

/**
 * Abstract class for service objects that are used by a controller plugin
 */
abstract class AbstractPlugin
{
    /**
     * @var Zend_Application_Bootstrap_Bootstrap
     */
    private static $bootstrap;

    /**
     * @param string $name
     * @return Zend_Application_Resource_ResourceAbstract
     * @throws Zend_Controller_Action_Exception
     */
    public function getResource($name)
    {
        if (!self::$bootstrap) {
            $fc = Zend_Controller_Front::getInstance();
            self::$bootstrap = $fc->getParam('bootstrap');
        }
        if (self::$bootstrap->hasResource($name)) {
            return self::$bootstrap->getResource($name);
        } else if (self::$bootstrap->hasPluginResource($name)) {
            return self::$bootstrap->getPluginResource($name);
        } else {
            $message = "Resource '$name' is not registered.";
            throw new Zend_Controller_Action_Exception($message, 500);
        }
    }
}
