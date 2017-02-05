<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;
use App\Enum\UserState;
use Mandragora\Model\AbstractModel;

class User extends CacheAbstract
{
    /**
     * @param string $username
     * @return array
     */
    public function findOneByUsernameAndStateActive($username)
    {
        $user = $this->getCache()->load('user' . md5(serialize($username)));
        if (!$user || $user['state'] != UserState::Active) {
            $user = $this->gateway->findOneByUsernameAndStateActive($username);
            $this->getCache()->save($user, 'user' . md5(serialize($username)));
        }
        return $user;
    }

    /**
     * @param string $username
     * @return array
     */
    public function findOneByUsername($username)
    {
        $user = $this->getCache()->load('user' . md5(serialize($username)));
        if (!$user) {
            $user = $this->gateway->findOneByUsername($username);
            $this->getCache()->save($user, 'user' . md5(serialize($username)));
        }
        return $user;
    }

    /**
     * @param string $confirmationKey
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByConfirmationKey($confirmationKey)
    {
        $client = $this->getCache()->load('client' . $confirmationKey);
        if (!$client) {
            $client = $this->gateway
                           ->findOneByConfirmationKey($confirmationKey);
            $this->getCache()->save($client, 'client' . $confirmationKey);
        }
        return $client;
    }

    /**
     * @param array $userInformation
     * @return int
     */
    public function updateClientAccountToConfirmed(array $userInformation)
    {
        $this->gateway->updateClientAccountToConfirmed($userInformation);
        $cache = $this->getCache();
        $cache->remove('client' . $userInformation['confirmationKey']);
        $cache->remove('user' .  md5(serialize($userInformation['username'])));
    }

    /**
     * @param string $userName
     * @param string $newState
     * @return void
     */
    public function updateUserState($userName, $newState)
    {
        $this->gateway->updateUserState($userName, $newState);
        $this->getCache()->remove('user' . md5(serialize($userName)));
    }

    /**
     * @param Mandragora_Model_Abstract $ser
     */
    public function insert(AbstractModel $user)
    {
        $this->gateway->insert($user);
        $this->getCache()->save(
            $user->toArray(), 'user' . md5(serialize($user->username))
        );
    }

    /**
     * @param Mandragora_Model_Abstract $user
     */
    public function update(AbstractModel $user)
    {
        $this->gateway->update($user);
        $this->getCache()->save(
            $user->toArray(), 'user' . md5(serialize($user->username))
        );
    }

    /**
     * @param Mandragora_Model_Abstract $user
     */
    public function delete(AbstractModel $user)
    {
        $this->gateway->delete($user);
        $this->getCache()->remove('user' . md5(serialize($user->username)));
    }
}
