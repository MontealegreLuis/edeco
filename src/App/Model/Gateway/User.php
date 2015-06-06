<?php
/**
 * Gateway for user model objects
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
 * @subpackage Gateway
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Gateway for user model objects
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Gateway
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Model_Gateway_User extends Mandragora_Gateway_Doctrine_Abstract
{
    /**
     * @param string $username
     * @return array
     */
    public function findOneByUsernameAndStateActive($username)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from('App_Model_Dao_User u')
              ->where('u.username = :username')
              ->andWhere('u.state = :state');
        $user = $query->fetchOne(
            array(
                ':username' => (string)$username,
                ':state' => App_Enum_UserState::Active,
            ),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$user) {
            $message = "User with username '$username' cannot be found";
            throw new Mandragora_Gateway_NoResultsFoundException($message);
        }
        return $user;
    }

    /**
     * @param string $username
     * @return array
     */
    public function findOneByUsername($username)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from('App_Model_Dao_User u')
              ->where('u.username = :username');
        $user = $query->fetchOne(
            array(':username' => (string)$username,),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$user) {
            $message = "User with username '$username' cannot be found";
            throw new Mandragora_Gateway_NoResultsFoundException($message);
        }
        return $user;
    }

    /**
     * @return array
     */
    public function getQueryFindAllClients()
    {
    	$query = $this->dao->getTable()->createQuery();
    	$query->from('App_Model_Dao_User u')
    	      ->where('u.roleName = "client"');
        return $query;
    }

    /**
     * @param string $confirmationKey
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByConfirmationKey($confirmationKey)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from('App_Model_Dao_User u')
              ->where('u.state = :state')
              ->andWhere('u.confirmationKey = :confirmationKey');
        $client = $query->fetchOne(
            array(
                ':state' => App_Enum_UserState::Unconfirmed,
                ':confirmationKey' => (string)$confirmationKey,
            ),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$client) {
            $message = "User with key '$confirmationKey' not found";
            throw new Mandragora_Gateway_NoResultsFoundException($message);
        }
        return $client;
    }

    /**
     * @param array $userInformation
     * @return int
     */
    public function updateClientAccountToConfirmed(array $userInformation)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->update('App_Model_Dao_User u')
              ->set('u.password', ':password')
              ->set('u.state', ':state')
              ->set('u.confirmationKey', ':confirmationKey')
              ->where('u.username = :username');
        $query->execute(
            array(
                ':password' => $userInformation['password'],
                ':state' => $userInformation['state'],
                ':confirmationKey' => null,
                ':username' => $userInformation['username'],
            )
        );
    }

    /**
     * @param string $userName
     * @param string $newState
     * @return void
     */
    public function updateUserState($userName, $newState)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->update('App_Model_Dao_User u')
              ->set('u.state', ':state')
              ->where('u.username = :username');
        $client = $query->execute(
            array(':state' => $newState, ':username' => $userName)
        );
    }

}