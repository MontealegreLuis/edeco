<?php
/**
 * Plugin for authentication and authorization
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
 * @subpackage Plugin
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Plugin for authentication and authorization.
 *
 * It verifies if the user is logged and has permission to access the current
 * action during the predispatch phase.
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Plugin
 * @history    20 may 2010
 *             LNJ
 *             - Class Creation
 */
class Edeco_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     * Verify if the user is logged and has permission to access the current
     * action. If not redirect to the "unauthorized action"
     *
     * @param $request
     *      The current request information
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $namespace = $bootstrap->getResource('settings')
                               ->getSetting('namespace');
        $cacheManager = $bootstrap->getResource('cachemanager');
        $options = array(
            'cacheManager' => $cacheManager, 'namespace' => $namespace
        );
        $aclHandler = Mandragora_Acl::factory('Handler', $namespace, $options);
        $aclHandler->execute($request);
        if ($aclHandler->isNotAuthenticated()) {
            //redirect to login
            $router = Mandragora_Service_Router::factory('Defaults', $namespace);
            if ($request->isGet()) {
                $session = new Zend_Session_Namespace(
                    Zend_Auth::getInstance()->getStorage()->getNamespace()
                );
                $session->requestUrl = $request->getRequestUri();
            }
            $url = $router->getDefaultRoute('login');
            $view = Zend_Layout::getMvcInstance()->getView();
            $flash = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'FlashMessenger'
            );
            $flash->setNamespace('error')
                  ->addMessage('Tu sesión expiró, vuelve a ingresar');
            $this->_response->setRedirect($view->url($url, 'index', true));
        } else if ($aclHandler->isNotAuthorized()) {
            //redirect to unauthorized page
            $router = Mandragora_Service_Router::factory('Defaults', $namespace);
            $url = $router->getDefaultRoute('unauthorized');
            $view = Zend_Layout::getMvcInstance()->getView();
            $this->_response->setRedirect($view->url($url, 'controllers', true));
        }
    }

}