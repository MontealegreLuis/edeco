<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Collection\City;
use App\Model\Collection\State;
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Gateway;
use Zend_Controller_Action_Exception as ActionException;

class CssController extends AbstractAction
{
    public function generateAction()
    {
        if (APPLICATION_ENV !== 'development') {
            throw new ActionException('Page not found', 404);
        } else {
            $gateway = Gateway::factory('State');
            $this->view->maps = $gateway->findAllMaps();
            $this->_helper->getHelper('layout')->disableLayout();
            $this->getResponse()->setHeader('Content-type', 'text/css');
        }
    }

    public function updateStateUrlAction()
    {
        $gateway = Gateway::factory('State');
        $states = new State($gateway->findAll());
        foreach ($states as $state) {
            $state->url = $state->name;
            $gateway->update($state);
        }
        echo 'States updated successfully!';
        die();
    }

    public function updateCityUrlAction()
    {
        $gateway = Gateway::factory('City');
        $cities = new City($gateway->findAll());
        foreach ($cities as $city) {
            $city->url = $city->name;
            $gateway->update($city);
        }
        echo 'Cities updated successfully!';
        die();
    }
}