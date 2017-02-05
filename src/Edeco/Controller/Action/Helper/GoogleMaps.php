<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Controller\Action\Helper;

use Zend_Controller_Action_Helper_Abstract;
use Zend_Config_Ini;
use Zend_Registry;
use Zend_Controller_Request_Abstract;

/**
 * Helper to initialize google maps key
 */
class GoogleMaps extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Gets Google Maps key from application.ini
     *
     * @return string
     */
    public function getGoogleMapsKey()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV
        );
        $options = $config->toArray();
        $googleMapsKey = $options['gdata'];
        Zend_Registry::set('googleMapsKey', $googleMapsKey['mapsKey']);
        return $googleMapsKey['mapsKey'];
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param Zend_Controller_Request_Abstract $request
     *      The request that's being processed
     * @return string
     */
    public function direct(Zend_Controller_Request_Abstract $request)
    {
        return $this->getGoogleMapsKey();
    }
}
