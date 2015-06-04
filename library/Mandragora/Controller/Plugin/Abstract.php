<?php
/**
 * Base class for controller plugins
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
 * @category   Library
 * @package    Mandragora
 * @subpackage Controller_Plugin
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class for controller plugins
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Controller_Plugin
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   Mandragora_Controller_Plugin_Abstract
extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Application_Bootstrap_Bootstrap
     */
    protected $bootstrap;

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $flash;

    /**
     * @param string $name
     * @return mixed
     */
    public function getResource($name)
    {
        if (is_null($this->bootstrap)) {
            $fc = Zend_Controller_Front::getInstance();
            $this->bootstrap = $fc->getParam('bootstrap');
        }
        if ($this->bootstrap->hasResource($name)) {
            return $this->bootstrap->getResource($name);
        } else if ($this->bootstrap->hasPluginResource($name)) {
            return $this->bootstrap->getPluginResource($name);
        } else {
            $message = "Resource '$name' is not registered.";
            throw new Zend_Controller_Action_Exception($message, 500);
        }
    }

    /**
     * @param $namespace = null
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function flash($namespace = null)
    {
        if (is_null($this->flash)) {
            $this->flash = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'FlashMessenger'
            );
        }
        return $this->flash;
    }

}