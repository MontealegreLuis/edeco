<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Action\Helper;

use Zend_Config_Ini;
use Zend_Navigation;
use RecursiveIteratorIterator;
use Zend_Controller_Action_Helper_Abstract as HelperAbstract;
use Zend_Controller_Request_Abstract as Request;

/**
 * Breadcrumbs builder
 */
class Breadcrumbs extends HelperAbstract
{
    /**
     * Sets the params for the routes of the current controller, in order to
     * build a breadcrumb
     *
     * @param  string $module
     * @param  array $controller
     * @return Zend_Navigation
     */
    public function buildBreadcrumbs($module, $controller, $params)
    {
        $pathToConfig = APPLICATION_PATH . sprintf(
            '/configs/breadcrumbs/%s/%s.ini', $module, $controller
        );
        $config = new Zend_Config_Ini($pathToConfig, APPLICATION_ENV);
        $container = new Zend_Navigation($config);
        $it = new RecursiveIteratorIterator(
            $container, RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $page) {
            foreach ($page->getParams() as $key => $value) {
                if (isset($params[$key])) {
                    $pageParams = $page->getParams();
                    $pageParams[$key] = $params[$key];
                    $page->setParams($pageParams);
                }
            }
        }
        return $container;
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return Zend_Navigation
     */
    public function direct(Request $request)
    {
        return $this->buildBreadcrumbs(
            $request->getModulename(), $request->getControllerName(),
            $request->getParams()
        );
    }
}
