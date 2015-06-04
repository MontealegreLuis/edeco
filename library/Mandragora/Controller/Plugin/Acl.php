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
 * @package    Mandragora
 * @subpackage Controller_Plugin
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Plugin for authentication and authorization.
 *
 * It verifies if the user is logged and has permission to access the current
 * action during the predispatch phase.
 *
 * @category   Application
 * @package    Mandragora
 * @subpackage Controller_Plugin
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   Mandragora_Controller_Plugin_Acl
extends Mandragora_Controller_Plugin_Abstract
{
    /**
     * Verify if the user is logged and has permission to perform the current
     * action. If not redirect to the "unauthorized action"
     *
     * @param $request
     *      The current request information
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $sessionOptions = $this->getResource('session')->getOptions();
        $options = array(
            'cacheManager' => $this->getResource('cachemanager'),
            'doctrineManager' => $this->getResource('doctrine'),
            'namespace' => $sessionOptions['name'],
        );
        $aclHandler = Mandragora_Acl::factory('Handler', $options);
        $aclHandler->execute($request);
        if ($aclHandler->isNotAuthenticated()) {
            //redirect to login
            $router = Mandragora_Service_Router::factory('Helper');
            if ($request->isGet()) {
                $name = Zend_Auth::getInstance()->getStorage()->getNamespace();
                $session = new Zend_Session_Namespace($name);
                $session->requestUrl = $request->getRequestUri();
            }
            $url = $router->getDefaultRoute('login');
            $view = Zend_Layout::getMvcInstance()->getView();
            $this->flash('error')->addMessage('session.sessionExpired');
            $route = $url['route'];
            unset($url['route']);
            $this->_response->setRedirect($view->url($url, $route, true));
        } else if ($aclHandler->isNotAuthorized()) {
            //redirect to unauthorized page
            $router = Mandragora_Service_Router::factory('Helper');
            $url = $router->getDefaultRoute('unauthorized');
            $view = Zend_Layout::getMvcInstance()->getView();
            $route = $url['route'];
            unset($url['route']);
            $this->_response->setRedirect($view->url($url, $route, true));
        }
    }

}