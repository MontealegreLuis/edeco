<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * Application's default controller
 */
class IndexController extends AbstractAction
{
    /**
     * @var array
     */
    protected $validMethods = [
        'mail-confirmation' => ['method' => 'post'],
        'premise-confirmation' => ['method' => 'post'],
    ];

    /**
     * Landing page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->setPropertyService();
        $properties = $this->service->retrieveAllPropertiesWithPictures(1);
        if ($properties->count() > 0) {
            $json = $this->service->propertiesToJson($properties->toArray(true));
            $this->view->dtoProperties = $json;
        } else {
            $this->view->dtoProperties = '[]';
        }
        $this->view->properties = $properties;
    }

    /**
     * Display the information about Edeco's history, mission and vision
     *
     * @return void
     */
    public function aboutAction() {}

    /**
     * Display the information about Edeco's objectives
     *
     * @return void
     */
    public function objectivesAction() {}

    /**
     * Display Edeco's contact information
     *
     * @return void
     */
    public function contactAction() {}

    /**
     * Send an email to Edeco's inbox
     *
     * @return void
     */
    public function formAction()
    {
        $this->setContactService();
        $url = $this->view->url(array('action' => 'mail-confirmation'), 'index');
        $contactForm = $this->service->getContactForm($url);
        if ($this->param('id')) {
            $propertyId = (int)$this->param('id');
            $contactForm->getElement('propertyId')->setValue($propertyId);
        }
        $this->view->contactForm = $contactForm;
    }

    /**
     * Show mail confirmation message
     */
    public function mailConfirmationAction()
    {
        $this->setContactService();
        $contactInformation = $this->post();
        $url = $this->view->url(array('action' => 'mail-confirmation'), 'index');
        $contactForm = $this->service->getContactForm($url);
        if ($contactForm->isValid($contactInformation)) {
            $baseUrl = $this->_helper->emailTransport($this->getRequest());
            $this->service->sendEmailMessage($baseUrl);
        } else {
            $this->view->contactForm = $contactForm;
            $this->renderScript('index/form.phtml');
        }
    }

    /**
     * @return void
     */
    protected function setContactService()
    {
        $this->service = Service::factory('Contact');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
    }

    /**
     * Shows a form to be send to EDECO when a user request specific information
     */
    public function premiseAction()
    {
        $this->setPremiseInformationService();
        $action = $this->view->url(
            array('action' => 'premise-confirmation'), 'index'
        );
        $this->view->premiseForm = $this->service->getPremiseForm($action);
    }

    /**
     * Show premise's mail confirmation page
     *
     * @return void
     */
    public function premiseConfirmationAction()
    {
        $this->setPremiseInformationService();
        $premiseInformation = $this->post();
        $action = $this->view->url(
            array('action' => 'premise-confirmation'), 'index'
        );
        $premiseForm = $this->service->getPremiseForm($action);
        if ($premiseForm->isValid($premiseInformation)) {
            $baseUrl = $this->_helper->emailTransport($this->getRequest());
            $this->service->sendEmailMessage($baseUrl);
        } else {
            $this->view->premiseForm = $premiseForm;
            $this->renderScript('index/premise.phtml');
        }
    }

    /**
     * @return void
     */
    protected function setPremiseInformationService()
    {
        $this->service = Service::factory('PremiseInformation');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
    }

    /**
     * @return void
     */
    protected function setPropertyService()
    {
        $this->service = Service::factory('Property');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
    }

    /**
     * Display the information of accessibility
     *
     * @return void
     */
    public function accessibilityAction(){}

    /**
     * Display the information legal of Edeco
     *
     * @return void
     */
    public function legalAction(){}

    /**
     * Display the information site map of Edeco
     *
     * @return void
     */
    public function sitemapAction()
    {
        $this->view->sitemap = $this->getSitemap();
    }

    /**
     * XML Sitemap
     */
    public function mapAction()
    {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $container = $this->getSitemap();
        $this->view->navigation()->sitemap($container)->setFormatOutput(true);
        echo $this->view->navigation($container)->sitemap();
    }

    /**
     * @return Zend_Navigation
     */
    protected function getSitemap()
    {
        $path = APPLICATION_PATH . '/configs/navigation/default.xml';
        $config = new Zend_Config_Xml($path, 'nav');
        $container = new Zend_Navigation($config);
        $categoryService = $this->getCategoryService();
        $categoryService->addCategoriesToSitemap($container);
        $this->setPropertyService();
        $this->service->addPropertiesToSitemap($container);
        return $container;
    }

    /**
     * @return App_Service_Category
     */
    protected function getCategoryService()
    {
        $service = Mandragora_Service::factory('Category');
        $service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $service->setDoctrineManager($doctrine);
        return $service;
    }

    /**
     * @return void
     */
    public function constructionAction() {}

}