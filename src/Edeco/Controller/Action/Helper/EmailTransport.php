<?php
/**
 * Helper to initialize e-mail transport configuration
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
 * @subpackage Action_Helper
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Helper to initialize e-mail transport configuration
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Action_Helper
 * @author     LMV <luis.montealgre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Controller_Action_Helper_EmailTransport
    extends Zend_Controller_Action_Helper_Abstract
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
                new Mandragora_Mail_Transport_Debug()
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