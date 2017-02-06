<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * City controller
 */
class Admin_CityController extends AbstractAction
{
    /**
     * @var array
     */
    protected $validMethods = [
        'find' => ['method' => 'xmlHttpRequest'],
    ];

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('City');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
    }

    /**
     * Retrieves cities information from an ajax call
     *
     * @return void
     */
    public function searchAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
    	$stateId = $this->param($this->view->translate('stateId'));
    	$cities = $this->service->retrieveAllByStateId($stateId);
        $this->_helper->json($cities);
    }
}
