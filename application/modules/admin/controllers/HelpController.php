<?php
class Admin_HelpController extends Mandragora_Controller_Action_Abstract
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

    public function loadNavigation($filename)
    {
        $config = new Zend_Config_Xml(
            APPLICATION_PATH . "/configs/navigation/help/$filename.xml", 'nav'
        );
        $container = new Zend_Navigation($config);
        $this->view->helpMenu = $container;
    }

}