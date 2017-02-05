<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Auth;

use Zend_Auth_Adapter_Interface;
use App\Model\User;
use Mandragora\Gateway\NoResultsFoundException;
use Exception;
use Zend_Auth_Adapter_Exception;
use Zend_Auth_Result;
use Mandragora\Model;

/**
 * The authentication adapter for handling users login/logout process
 */
class Adapter implements Zend_Auth_Adapter_Interface
{
    /**
     * @var Edeco_Model_User
     */
    protected $user;

    /**
     * @var Edeco_Model_Gateway_User
     */
    protected $userGateway;

    /**
     * @param Edeco_Model_User $user
     * @param Mandragora_Gateway_Interface
     *        | Mandragora_Gateway_Decorator_CacheAbstract $userGateway
     */
    public function __construct(User $user, $userGateway)
    {
        $this->user = $user;
        $this->userGateway = $userGateway;
    }

    /**
     * @return Zend_Auth_Result
     * @throws Zend_Auth_Adapter_Exception
     */
    public function authenticate()
    {
        try {
            $userFound = $this->userGateway->findOneByUsernameAndStateActive(
                $this->user->username
            );
        } catch(NoResultsFoundException $e) {
            $userFound = null;
        } catch (Exception $e) {
            throw new Zend_Auth_Adapter_Exception($e->getMessage());
        }
        return $this->createAuthenticationResult($userFound);
    }

    /**
     * Create the apropriate result object according to query result
     *
     * @return Zend_Auth_Result
     */
    protected function createAuthenticationResult($userFound)
    {
        if ($userFound == null) {
            return $this->createResultIdentityNotFound();
        } elseif ($this->user->password != $userFound['password']) {
            return $this->createResultCredentialInvalid();
        }
        return $this->createResultAuthenticationSucceed($userFound);
    }

    /**
     * Create the result with identity not found details
     *
     * @return Zend_Auth_Result
     */
    protected function createResultIdentityNotFound()
    {
        return new Zend_Auth_Result(
            Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
            null,
            array('username' => 'Su nombre de usuario no existe')
        );
    }

    /**
     * @params array $userInformation
     * @return Zend_Auth_Result
     */
    protected function createResultAuthenticationSucceed($userInformation)
    {
    	$identity = Model::factory('User', $userInformation);
        $identity->password = null;
        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $identity);
    }

    /**
     * Create a result with password invalida details
     *
     * @return Zend_Auth_Result
     */
    protected function createResultCredentialInvalid()
    {
        return new Zend_Auth_Result(
            Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
            null,
            array('password' => 'Su password es incorrecto')
        );
    }
}
