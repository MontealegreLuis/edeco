<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Zend_Config_Xml as XmlConfig;
use Zend_Navigation as Navigation;

class Admin_HelpController extends AbstractAction
{
    public function init()
    {
        $breadcrumbs = $this->_helper->breadcrumbsBuilder($this->getRequest());
        $this->view->breadcrumbs = $breadcrumbs;
    }

    public function showAction()
    {
        $topic = $this->param('topic', 'index');
        $operation = $this->param('operation', 'index');
        $this->loadNavigation($topic);
        $this->renderScript("help/$topic/$operation.phtml");
    }

    public function loadNavigation(string $filename)
    {
        $config = new XmlConfig(
            APPLICATION_PATH . "/configs/navigation/help/$filename.xml", 'nav'
        );
        $this->view->helpMenu = new Navigation($config);
    }
}
