<?php
class   CssController
extends Mandragora_Controller_Action_Abstract
{
    public function generateAction()
    {
        if (APPLICATION_ENV !== 'development') {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        } else {
            $gateway = Mandragora_Gateway::factory('State');
            $this->view->maps = $gateway->findAllMaps();
            $this->_helper->getHelper('layout')->disableLayout();
            $this->getResponse()
                 ->setHeader('Content-type', 'text/css');
        }
    }

    public function updateStateUrlAction()
    {
        $gateway = Mandragora_Gateway::factory('State');
        $states = new App_Model_Collection_State($gateway->findAll());
        foreach ($states as $state) {
            $state->url = $state->name;
            $gateway->update($state);
        }
        echo 'States updated successfully!';
        die();
    }

    public function updateCityUrlAction()
    {
        $gateway = Mandragora_Gateway::factory('City');
        $cities = new App_Model_Collection_City($gateway->findAll());
        foreach ($cities as $city) {
            $city->url = $city->name;
            $gateway->update($city);
        }
        echo 'Cities updated successfully!';
        die();
    }

}