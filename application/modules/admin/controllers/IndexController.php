<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\Auth;
use Mandragora\Service;
use Mandragora\Service\Router;

/**
 * Perform authentication process
 */
class Admin_IndexController extends Auth
{
    /**
     * @var array
     */
    protected $validMethods = ['authenticate' => ['method' => 'post']];

    /**
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('User');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
    }

	/**
     * Redirect the user to the login page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->redirectToRoute('login');
    }

    /**
     * Show user login form
     *
     * @return void
     */
    public function loginAction()
    {
        if ($this->service->isUserLogged()) {
            $this->redirectToInitialPage();
        }
        $loginForm = $this->service->getForm('Login');
        $action = $this->view->url(array('action' => 'authenticate'));
        $loginForm->setAction($action);
        $loginForm->addHash($this->getAppSetting('csrf'));
        $this->view->loginForm = $loginForm;
    }

    /**
     * @return void
     */
    public function authenticateAction()
    {
        $loginForm = $this->service->getForm('Login');
        $loginForm->addHash($this->getAppSetting('csrf'));
        if ($loginForm->isValid($this->post())) {
            $result = $this->service->login();
            if ($result->isValid()) {
                $auth = Zend_Auth::getInstance();
                $namespace = $auth->getStorage()->getNamespace();
                $session = new Zend_Session_Namespace($namespace);
                $session->setExpirationSeconds(3600);
                $this->redirectToInitialPage();
            } else  {
                $loginForm->setAuthenticationErrors($result->getMessages());
            }
        }
        $this->view->loginForm = $loginForm;
        $this->renderScript('index/login.phtml');
    }

    /**
     * Redirect the user to her initial page depending on her role
     */
    protected function redirectToInitialPage()
    {
        $namespace = Zend_Auth::getInstance()->getStorage()->getNamespace();
        $session = new Zend_Session_Namespace($namespace);
        $expirationSeconds = $this->getSessionOption('gc_maxlifetime');
        $session->setExpirationSeconds($expirationSeconds);
        if (isset($session->requestUrl)) {
            $url = $session->requestUrl;
            unset($session->requestUrl);
            $this->_redirect($url, array('prependBase' => false));
        } else {
            $identity = Zend_Auth::getInstance()->getIdentity();
            $router = Router::factory('Helper');
            $url = $router->getDefaultRoute($identity->roleName);
            $this->redirectToRoute(
                $url['action'], [], $url['controller'], $url['module'], $url['route']
            );
        }
    }

    /**
     * User logout
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->service->logout();
        $this->_redirect(
            $this->view->url(array('action' => 'login'), 'index'),
            array('prependBase' => false)
        );
    }
}
