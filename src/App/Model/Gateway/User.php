<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Enum\UserState;
use Doctrine_Core;
use Mandragora\Gateway\Doctrine\DoctrineGateway;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for user model objects
 */
class User extends DoctrineGateway
{
    /**
     * @param string $username
     * @return array
     * @throws \Mandragora\Gateway\NoResultsFoundException
     */
    public function findOneByUsernameAndStateActive($username)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('u.username = :username')
              ->andWhere('u.state = :state');
        $user = $query->fetchOne(
            [
                ':username' => (string) $username,
                ':state' => UserState::Active,
            ],
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
     * @throws \Mandragora\Gateway\NoResultsFoundException
     */
    public function findOneByUsername($username)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('u.username = :username');
        $user = $query->fetchOne(
            [':username' => (string)$username,],
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$user) {
            $message = "User with username '$username' cannot be found";
            throw new NoResultsFoundException($message);
        }
        return $user;
    }

    /**
     * @return \Doctrine_Query
     */
    public function getQueryFindAllClients()
    {
    	$query = $this->dao->getTable()->createQuery();
    	$query->from($this->alias())
    	      ->where('u.roleName = "client"');
        return $query;
    }

    /**
     * @param string $confirmationKey
     * @return array
     * @throws \Mandragora\Gateway\NoResultsFoundException
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByConfirmationKey($confirmationKey)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('u.state = :state')
              ->andWhere('u.confirmationKey = :confirmationKey');
        $client = $query->fetchOne(
            [
                ':state' => UserState::Unconfirmed,
                ':confirmationKey' => (string)$confirmationKey,
            ],
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
     */
    public function updateClientAccountToConfirmed(array $userInformation)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->update($this->alias())
              ->set('u.password', ':password')
              ->set('u.state', ':state')
              ->set('u.confirmationKey', ':confirmationKey')
              ->where('u.username = :username');
        $query->execute([
            ':password' => $userInformation['password'],
            ':state' => $userInformation['state'],
            ':confirmationKey' => null,
            ':username' => $userInformation['username'],
        ]);
    }

    /**
     * @param string $userName
     * @param string $newState
     * @return void
     */
    public function updateUserState($userName, $newState)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->update($this->alias())
              ->set('u.state', ':state')
              ->where('u.username = :username');
        $query->execute([
            ':state' => $newState, ':username' => $userName
        ]);
    }
}
