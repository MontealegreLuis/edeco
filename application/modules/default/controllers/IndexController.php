<?php
/**
 * Application's default controller
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN $Id$
 */

/**
 * Application's default controller
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN $Id$
 */
class IndexController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'mail-confirmation' => array('method' => 'post'),
        'premise-confirmation' => array('method' => 'post'),
    );

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
        $this->service = Mandragora_Service::factory('Contact');
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
        $this->service = Mandragora_Service::factory('PremiseInformation');
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
        $this->service = Mandragora_Service::factory('Property');
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