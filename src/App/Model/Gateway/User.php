<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;
use App\Enum\UserState;
use Doctrine_Core;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for user model objects
 */
class User extends AbstractDoctrine
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
                ':state' => UserState::Active,
            ),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$user) {
            $message = "User with username '$username' cannot be found";
            throw new NoResultsFoundException($message);
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
            throw new NoResultsFoundException($message);
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
                ':state' => UserState::Unconfirmed,
                ':confirmationKey' => (string)$confirmationKey,
            ),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$client) {
            $message = "User with key '$confirmationKey' not found";
            throw new NoResultsFoundException($message);
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
