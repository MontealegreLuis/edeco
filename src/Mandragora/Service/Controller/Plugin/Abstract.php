<?php
/**
* Abstract class for service objects that are used by a controller plugin
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
* @subpackage Service_Controller_Plugin
* @author     LMV <luis.montealegre@mandragora-web-systems.com>
* @copyright  Mandrágora Web-Based Systems 2010
* @version    SVN: $Id$
*/

/**
 * Abstract class for service objects that are used by a controller plugin
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Service_Controller_Plugin
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class Mandragora_Service_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Application_Bootstrap_Bootstrap
     */
    private static $bootstrap;

    /**
     * @param string $name
     * @return Zend_Application_Resource_ResourceAbstract
     * @throws Zend_Controller_Action_Exception
     */
    public function getResource($name)
    {
        if (!self::$bootstrap) {
            $fc = Zend_Controller_Front::getInstance();
            self::$bootstrap = $fc->getParam('bootstrap');
        }
        if (self::$bootstrap->hasResource($name)) {
            return self::$bootstrap->getResource($name);
        } else if (self::$bootstrap->hasPluginResource($name)) {
            return self::$bootstrap->getPluginResource($name);
        } else {
            $message = "Resource '$name' is not registered.";
            throw new Zend_Controller_Action_Exception($message, 500);
        }
    }

}