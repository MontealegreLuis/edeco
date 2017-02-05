<?php
/**
 * Breadcrumbs builder for Edeco's application.
 *
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Action\Helper;

use Zend_Controller_Action_Helper_Abstract;
use Zend_Config_Xml;
use Zend_Navigation;
use Zend_Controller_Request_Abstract;

/**
 * Breadcrumbs builder for Edeco's application.
 */
class BreadcrumbsBuilder extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Sets the params for the routes of the current controller, in order to
     * build a breadcrumb
     *
     * @param  string $controller
     *      The current controller name
     * @param  array $params
     *      The request parameters
     * @return Zend_Navigation
     *      The breadcrumbs container
     */
    public function buildBreadcrumbs($topic)
    {
        $pathToConfig = APPLICATION_PATH . sprintf(
            '/configs/breadcrumbs/help/%s.xml', $topic
        );
        $config = new Zend_Config_Xml($pathToConfig, 'nav');
        $container = new Zend_Navigation($config);
        //$it = new RecursiveIteratorIterator(
        //    $container, RecursiveIteratorIterator::SELF_FIRST
        //);
        //foreach ($it as $page) {
        //    $page->setOptions(array('params' => $params));
        //}
        return $container;
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return Zend_Navigation
     */
    public function direct(Zend_Controller_Request_Abstract $request)
    {
        return $this->buildBreadcrumbs(
            $request->getParam('topic', 'index')
            //$request->getParam('operation', 'index')
        );
    }
}
