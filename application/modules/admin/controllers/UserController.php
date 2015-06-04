<?php
/**
 * Application's User controller
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
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Application's User controller
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Admin_UserController extends Mandragora_Controller_Action_Abstract
{
	/**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' => array('method' => 'post'),
    );

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('User');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * Show all the available properties
     *
     * @return void
     */
    public function listAction()
    {
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $page = (int)$this->param($this->view->translate('page'), 1);
        $this->service->openConnection();
        $users =$this->service->retrieveAllClientUsers($page);
        $this->view->users = $users;
        $this->view->paginator = $this->service->getPaginator($page);
    }

    /**
     * Add a user to the database and send a verification e-mail to the new user
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $this->view->userForm = $this->service->getFormForCreating($action);
    }

    /**
     * Save the user information to the data source or show the
     * corresponding error messages if needed
     *
     *  @retun void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $userForm = $this->service->getFormForCreating($action);
        if ($userForm->isValid($this->post())) {
        	$baseUrl = $this->_helper->emailTransport($this->getRequest());
        	$this->service->openConnection();
        	$this->service->createUser($baseUrl);
            $this->flash()->addMessage(array('success' => 'user.created'));
            $userName = $this->view->translate('username');
            $params = array($userName => $this->service->getModel()->username);
            $this->redirect('show', $params);
        } else {
            $this->view->userForm = $userForm;
            $this->renderScript('user/create.phtml');
        }
    }

    /**
     * Show the user information, including the address and Google maps info
     *
     * @return void
     */
    public function showAction()
    {
        $username = (string)$this->param($this->view->translate('username'));
        $this->service->openConnection();
        $user = $this->service->retrieveUserByUsername($username);
        if (!$user) {
            $this->flash('error')->addMessage('user.not.found');
            $this->redirect('list', array($this->view->translate('page') => 1));
        } else {
            $user->prepareShowing();
            $this->view->user = $user;
        }
    }

    /**
     * Change a user's state
     */
    public function editAction()
    {
        $username = $this->param($this->view->translate('username'));
        $this->service->openConnection();
        $user = $this->service->retrieveUserByUsername((string)$username);
        if (!$user) {
            $this->flash('error')->addMessage('user.not.found');
            $this->redirect('list', array('page' => 1));
        } else {
            $action = $this->view->url(array('action' => 'update'));
            $userForm = $this->service->getFormForEditing($action);
            $userForm->populate($user->toArray());
            $userForm->removeInvalidStates($userForm->getValue('state'));
            $this->view->user = $user;
            $this->view->userForm = $userForm;
        }
    }

    /**
     * @return void
     */
    protected function updateAction()
    {
        $action = $this->view->url(array('action' => 'update'), 'controllers');
        $userForm = $this->service->getFormForEditing($action);
        $userValues = $this->post();
        if ($userForm->isValid($userValues)) {
            $username = $this->post('username');
            $this->service->openConnection();
            $user = $this->service->retrieveUserByUsername($username);
            if (!$user) {
                $this->flash('error')->addMessage('user.not.found');
                $this->redirect('list', array('page' => 1));
            } else {
                if ($user->version > $userValues['version']) {
                    $this->flash('error')->addMessage(
                        'user.optimistic.locking.failure'
                    );
                    $userForm->populate($user->toArray());
                    $this->view->userForm = $userForm;
                    $this->renderScript('user/edit.phtml');
                } else {
                    $this->service->updateUser();
                    $this->flash('success')->addMessage('user.updated');
                    $username = $this->view->translate('username');
                    $params = array($username => $user->username);
                    $this->redirect('show', $params);
                }
            }
        } else {
            $values = $userForm->getValues();
            $this->view->user = new App_Model_User($values);
            $this->view->userForm = $userForm;
            $this->renderScript('user/edit.phtml');
        }
    }

    /**
     * @return void
     */
    public function deleteAction()
    {
    	$this->service->openConnection();
        $userName = $this->param($this->view->translate('username'));
        $this->service->deleteUser((string)$userName);
        $this->flash('error')->addMessage('user.deleted');
        $this->redirect('list', array('page' => 1));
    }

    /**
     * Confirms client account creation by creating a strong password
     *
     * @return void
     */
    public function confirmAction()
    {
        $paramKey = $this->view->translate('confirmationKey');
		$confirmationKey = (string)$this->param($paramKey);
		$this->service->openConnection();
		$result = $this->service
		               ->confirmClientAccountCreation($confirmationKey);
		if ($result->isValid()) {
            $this->view->password = $this->service
                                         ->getNewPassword($confirmationKey);
		} else if (
		  $result->getCode() == Edeco_Model_ConfirmationResult::USER_NOT_FOUND
        ) {
	        throw new Zend_Controller_Action_Exception(
                "Key $confirmationKey was not found", 404
            );
	    }
	    $this->view->code = $result->getCode();
    }

}