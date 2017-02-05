<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Plugin;

use Zend_Controller_Request_Abstract;
use Mandragora\Acl as MandragoraAcl;
use Mandragora\Service\Router;
use Zend_Auth;
use Zend_Session_Namespace;
use Zend_Layout;

/**
 * Plugin for authentication and authorization.
 *
 * It verifies if the user is logged and has permission to access the current
 * action during the pre-dispatch phase.
 */
class Acl extends AbstractPlugin
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
        $options = [
            'cacheManager' => $this->getResource('cachemanager'),
            'doctrineManager' => $this->getResource('doctrine'),
            'namespace' => $sessionOptions['name'],
        ];
        $aclHandler = MandragoraAcl::factory('Handler', $options);
        $aclHandler->execute($request);
        if ($aclHandler->isNotAuthenticated()) {
            //redirect to login
            $router = Router::factory('Helper');
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
            $router = Router::factory('Helper');
            $url = $router->getDefaultRoute('unauthorized');
            $view = Zend_Layout::getMvcInstance()->getView();
            $route = $url['route'];
            unset($url['route']);
            $this->_response->setRedirect($view->url($url, $route, true));
        }
    }
}
