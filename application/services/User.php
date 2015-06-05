<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Service class for User's model
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class App_Service_User extends Mandragora_Service_Crud_Doctrine_Abstract
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
     * @return Zend_Auth_Result
     */
    public function login()
    {
        $this->init();
        $user = $this->getModel();
        $user->username = $this->getForm()->getValue('username');
        $user->password = $this->getForm()->getValue('password');
        $adapter = new Edeco_Auth_Adapter($user, $this->getGateway());
        $authenticator = Zend_Auth::getInstance();
        return $authenticator->authenticate($adapter);
    }

    /**
     * @param array $errors
     * @return void
     */
    public function setLoginFormAuthenticationErrors(array $errors)
    {
        foreach ($errors as $name => $message) {
            $this->getLoginForm()
                 ->getElement($name)
                 ->setErrors(array($message));
        }
    }

    /**
     * Clears the user's identity
     *
     * @return void
     */
    public function logout()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
    }

    /**
     * @return boolean
     */
    public function isUserLogged()
    {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    /**
     * @return Edeco_Model_Collection_User
     */
    public function retrieveAllClientUsers($pageNumber)
    {
    	$query = $this->getGateway()->getQueryFindAllClients();
        $this->setPaginatorQuery($query);
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new App_Model_Collection_User($items);
    }

    /**
     * @return void
     */
    public function createUser($baseUrl)
    {
        $username = $this->getForm()->getValue('username');
        $this->getModel()->createClientAccount((string)$username);
        $this->getGateway()->insert($this->getModel());
        $this->getModel()->sendEmailConfirmationMessage(
            $this->getModel()->username,
            $this->getModel()->confirmationKey,
            $baseUrl
        );
    }

    /**
     * @param string $confirmationKey
     * @return Edeco_Model_ConfirmationResult
     */
    public function confirmClientAccountCreation($confirmationKey)
    {
    	$this->init();
        return new App_Model_ConfirmationResult(
            $confirmationKey, $this->getGateway()
        );
    }

    /**
     * @param string $confirmationKey
     * @return string
     *      An auto-generated strong password in raw form
     * @throws Mandragora_Gateway_Doctrine_NoResultsFoundException
     */
    public function getNewPassword($confirmationKey)
    {
        $userInformation = $this->getGateway()
                                ->findOneByConfirmationKey($confirmationKey);
        $this->getModel()->fromArray($userInformation);
        $password = $this->getModel()->confirmClientAccountCreation();
        $this->getGateway()
             ->updateClientAccountToConfirmed($this->getModel()->toArray());
        return $password;
    }

    /**
     * @param string $userName
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function retrieveUserByUsername($userName)
    {
        try {
            $userValues = $this->getGateway()->findOneByUsername($userName);
            return new App_Model_User($userValues);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function updateUser()
    {
        $userName = (string)$this->getForm()->getValue('username');
        $newState = (string)$this->getForm()->getValue('state');
        $this->getGateway()->updateUserState($userName, $newState);
    }

    /**
     * @param string $userName
     * @return void
     */
    public function deleteUser($userName)
    {
        $this->getModel()->username = (string)$userName;
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @param string $action
     * @return Edeco_Form_User_Detail
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
     * @return Edeco_Form_User_Detail
     */
    public function getFormForEditing($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        $this->getForm()->setState(App_Enum_UserState::values());
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

}