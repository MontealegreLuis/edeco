<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Action\Helper;

use Zend_Controller_Action_Helper_Abstract;
use Zend_Config_Xml;
use Zend_Navigation;
use Zend_Controller_Request_Abstract;

class ActionsBuilder extends Zend_Controller_Action_Helper_Abstract
{
    public function buildActions($modelName, array $params)
    {
        $pathToConfig = APPLICATION_PATH . sprintf(
            '/configs/navigation/actions/%s.xml', $modelName
        );
        $config = new Zend_Config_Xml($pathToConfig, 'nav');
        $container = new Zend_Navigation($config);
        return $container;
    }

    public function direct(Zend_Controller_Request_Abstract $request)
    {
        return $this->buildActions(
            $request->getControllerName(), $request->getParams()
        );
    }
}