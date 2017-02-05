<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Controller\Plugin;

use Zend_Controller_Plugin_Abstract;
use Zend_Controller_Request_Abstract;
use Zend_Controller_Front;
use Mandragora\Acl as MandragoraAcl;
use Mandragora\Service\Router;
use Zend_Session_Namespace;
use Zend_Auth;
use Zend_Layout;
use Zend_Controller_Action_HelperBroker;

/**
 * Plugin for authentication and authorization.
 *
 * It verifies if the user is logged and has permission to access the current
 * action during the pre-dispatch phase.
 */
class Acl extends Zend_Controller_Plugin_Abstract
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
        $aclHandler = MandragoraAcl::factory('Handler', $namespace, $options);
        $aclHandler->execute($request);
        if ($aclHandler->isNotAuthenticated()) {
            //redirect to login
            $router = Router::factory('Defaults', $namespace);
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
            $router = Router::factory('Defaults', $namespace);
            $url = $router->getDefaultRoute('unauthorized');
            $view = Zend_Layout::getMvcInstance()->getView();
            $this->_response->setRedirect($view->url($url, 'controllers', true));
        }
    }
}
