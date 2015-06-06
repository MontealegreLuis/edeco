<?php
/**
 * The authentication adapter for handling users login/logout process
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
 * @category   Panel
 * @package    Mandragora
 * @subpackage Auth
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * The authentication adapter for handling users login/logout process
 *
 * @category   Panel
 * @package    Mandragora
 * @subpackage Auth
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Auth_Adapter implements Zend_Auth_Adapter_Interface
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
    public function __construct(App_Model_User $user, $userGateway)
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
        } catch(Mandragora_Gateway_NoResultsFoundException $e) {
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
    	$identity = Mandragora_Model::factory('User', $userInformation);
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