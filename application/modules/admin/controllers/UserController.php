<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\ConfirmationResult;
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;
use Zend_Controller_Action_Exception as ActionException;

/**
 * Application's User controller
 */
class Admin_UserController extends AbstractAction
{
	/** @var array */
    protected $validMethods = [
        'save' => ['method' => 'post'],
        'update' => ['method' => 'post'],
    ];

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('User');
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
        $page = (int) $this->param($this->view->translate('page'), 1);
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
        $action = $this->view->url(['action' => 'save'], 'controllers');
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
        $action = $this->view->url(['action' => 'save'], 'controllers');
        $userForm = $this->service->getFormForCreating($action);
        if ($userForm->isValid($this->post())) {
        	$baseUrl = $this->_helper->emailTransport($this->getRequest());
        	$this->service->openConnection();
        	$this->service->createUser($baseUrl);
            $this->flash()->addMessage(['success' => 'user.created']);
            $userName = $this->view->translate('username');
            $params = [$userName => $this->service->getModel()->username];
            $this->redirectToRoute('show', $params);
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
        $username = (string) $this->param($this->view->translate('username'));
        $this->service->openConnection();
        $user = $this->service->retrieveUserByUsername($username);
        if (!$user) {
            $this->flash('error')->addMessage('user.not.found');
            $this->redirectToRoute('list', [$this->view->translate('page') => 1]);
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
            $this->redirectToRoute('list', ['page' => 1]);
        } else {
            $action = $this->view->url(['action' => 'update']);
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
        $action = $this->view->url(['action' => 'update'], 'controllers');
        $userForm = $this->service->getFormForEditing($action);
        $userValues = $this->post();
        if ($userForm->isValid($userValues)) {
            $username = $this->post('username');
            $this->service->openConnection();
            $user = $this->service->retrieveUserByUsername($username);
            if (!$user) {
                $this->flash('error')->addMessage('user.not.found');
                $this->redirectToRoute('list', ['page' => 1]);
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
                    $params = [$username => $user->username];
                    $this->redirectToRoute('show', $params);
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
        $this->service->deleteUser((string) $userName);
        $this->flash('error')->addMessage('user.deleted');
        $this->redirectToRoute('list', ['page' => 1]);
    }

    /**
     * Confirms client account creation by creating a strong password
     *
     * @return void
     * @throws \Zend_Controller_Action_Exception
     */
    public function confirmAction()
    {
        $paramKey = $this->view->translate('confirmationKey');
		$confirmationKey = (string) $this->param($paramKey);
		$this->service->openConnection();
		$result = $this->service
		               ->confirmClientAccountCreation($confirmationKey);
		if ($result->isValid()) {
            $this->view->password = $this->service->getNewPassword($confirmationKey);
		} else if ($result->getCode() === ConfirmationResult::USER_NOT_FOUND) {
	        throw new ActionException("Key $confirmationKey was not found", 404);
	    }
	    $this->view->code = $result->getCode();
    }
}
