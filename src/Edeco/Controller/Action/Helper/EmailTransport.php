<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Controller\Action\Helper;

use Zend_Controller_Action_Helper_Abstract;
use Zend_Config_Ini;
use Zend_Mail;
use Mandragora\Mail\Transport\Debug;
use Zend_Mail_Transport_Smtp;
use Zend_Controller_Front;
use Zend_Controller_Request_Abstract;

/**
 * Helper to initialize e-mail transport configuration
 */
class EmailTransport extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Set default e-mail transport configuration
     *
     * @return void
     */
    public function setEmailTransportOptions()
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV
        );
        $options = $config->toArray();
        if (APPLICATION_ENV == 'development' || APPLICATION_ENV == 'testing') {
            Zend_Mail::setDefaultTransport(
                new Debug()
            );
        } else {
            $transport = new Zend_Mail_Transport_Smtp(
                $options['resources']['mail']['transport']['host'],
                $options['resources']['mail']['transport']
            );
            Zend_Mail::setDefaultFrom(
                $options['resources']['mail']['defaultFrom']['email'],
                $options['resources']['mail']['defaultFrom']['name']
            );
            Zend_Mail::setDefaultReplyTo(
                $options['resources']['mail']['defaultReplyTo']['email'],
                $options['resources']['mail']['defaultReplyTo']['name']
            );
            Zend_Mail::setDefaultTransport($transport);
        }
        $module = Zend_Controller_Front::getInstance()->getRequest()
                                                      ->getModuleName();
        return $module === 'default' ?
            $options['app']['templates']['baseUrl']
            : $options['app']['templates']['admin']['baseUrl'];
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param Zend_Controller_Request_Abstract $request
     *      The request that's being processed
     * @return void
     */
    public function direct(Zend_Controller_Request_Abstract $request)
    {
        return $this->setEmailTransportOptions();
    }
}
