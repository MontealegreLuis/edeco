<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Edeco\Auth\Adapter;
use Zend_Auth as Auth;
use Zend_Session as Session;
use App\Model\Collection\User as UserCollection;
use App\Model\ConfirmationResult;
use App\Model\User as UserModel;
use Mandragora\Gateway\NoResultsFoundException;
use App\Enum\UserState;

/**
 * Service class for User's model
 */
class User extends DoctrineCrud
{
    /**
     * Decorate the gateway in order to cache the query results
     *
     * @return void
     */
    public function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @return \Zend_Auth_Result
     */
    public function login()
    {
        $this->init();
        $user = $this->getModel();
        $user->username = $this->getForm()->getValue('username');
        $user->password = $this->getForm()->getValue('password');
        $authenticator = Auth::getInstance();
        return $authenticator->authenticate(new Adapter($user, $this->getGateway()));
    }

    /**
     * @param array $errors
     * @return void
     */
    public function setLoginFormAuthenticationErrors(array $errors)
    {
        foreach ($errors as $name => $message) {
            $this->getLoginForm()->getElement($name)->setErrors([$message]);
        }
    }

    /**
     * Clears the user's identity
     *
     * @return void
     */
    public function logout()
    {
        Auth::getInstance()->clearIdentity();
        Session::destroy();
    }

    /**
     * @return boolean
     */
    public function isUserLogged()
    {
        return Auth::getInstance()->hasIdentity();
    }

    /**
     * @return UserCollection
     */
    public function retrieveAllClientUsers(int $pageNumber)
    {
    	$query = $this->getGateway()->getQueryFindAllClients();
        $this->setPaginatorQuery($query);
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();
        return new UserCollection($items);
    }

    /**
     * @return void
     */
    public function createUser(string $baseUrl)
    {
        $username = $this->getForm()->getValue('username');
        $this->getModel()->createClientAccount((string) $username);
        $this->getGateway()->insert($this->getModel());
        $this->getModel()->sendEmailConfirmationMessage(
            $this->getModel()->username,
            $this->getModel()->confirmationKey,
            $baseUrl
        );
    }

    /**
     * @return ConfirmationResult
     */
    public function confirmClientAccountCreation(string $confirmationKey)
    {
    	$this->init();
        return new ConfirmationResult($confirmationKey, $this->getGateway());
    }

    /**
     * @return string An auto-generated strong password in raw form
     */
    public function getNewPassword(string $confirmationKey)
    {
        $userInformation = $this->getGateway()->findOneByConfirmationKey($confirmationKey);
        $this->getModel()->fromArray($userInformation);
        $password = $this->getModel()->confirmClientAccountCreation();
        $this->getGateway()->updateClientAccountToConfirmed($this->getModel()->toArray());
        return $password;
    }

    /**
     * @throws NoResultsFoundException
     */
    public function retrieveUserByUsername(string $userName)
    {
        try {
            $userValues = $this->getGateway()->findOneByUsername($userName);
            return new UserModel($userValues);
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function updateUser()
    {
        $userName = (string) $this->getForm()->getValue('username');
        $newState = (string) $this->getForm()->getValue('state');
        $this->getGateway()->updateUserState($userName, $newState);
    }

    /**
     * @param string $userName
     * @return void
     */
    public function deleteUser(string $userName)
    {
        $this->getModel()->username = $userName;
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @param string $action
     * @return \App\Form\User\Detail
     */
    public function getFormForCreating($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @param string $action
     * @return \App\Form\User\Detail
     */
    public function getFormForEditing($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        $this->getForm()->setState(UserState::values());
        return $this->getForm();
    }

    /**
     * @param string $formName
     * @return void
     */
    protected function createForm($formName)
    {
        $this->getForm($formName);
    }

    public function getModel(array $values = null): ?AbstractModel
    {
        if (!$this->model) {
            $this->model = new UserModel($values);
        }

        return $this->model;
    }
}
