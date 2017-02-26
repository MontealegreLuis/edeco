<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Auth;

use Zend_Auth_Adapter_Interface;
use App\Model\User;
use Mandragora\Gateway\NoResultsFoundException;
use Exception;
use Zend_Auth_Adapter_Exception as AdapterException;
use Zend_Auth_Result as Result;

/**
 * The authentication adapter for handling users login/logout process
 */
class Adapter implements Zend_Auth_Adapter_Interface
{
    /** @var User */
    protected $user;

    /** @var \App\Model\Gateway\User */
    protected $userGateway;

    /**
     * @param User $user
     * @param Mandragora_Gateway_Interface
     *        | Mandragora_Gateway_Decorator_CacheAbstract $userGateway
     */
    public function __construct(User $user, $userGateway)
    {
        $this->user = $user;
        $this->userGateway = $userGateway;
    }

    /**
     * @return Result
     * @throws AdapterException
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
            throw new AdapterException($e->getMessage());
        }
        return $this->createAuthenticationResult($userFound);
    }

    /**
     * Create the appropriate result object according to query result
     *
     * @return Result
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
     * @return Result
     */
    protected function createResultIdentityNotFound()
    {
        return new Result(
            Result::FAILURE_IDENTITY_NOT_FOUND,
            null,
            ['username' => 'Su nombre de usuario no existe']
        );
    }

    /**
     * @params array $userInformation
     * @return Result
     */
    protected function createResultAuthenticationSucceed($userInformation)
    {
    	$identity = new User($userInformation);
        $identity->password = null;
        return new Result(Result::SUCCESS, $identity);
    }

    /**
     * Create a result with password invalid details
     *
     * @return Result
     */
    protected function createResultCredentialInvalid()
    {
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['password' => 'Su password es incorrecto']
        );
    }
}
